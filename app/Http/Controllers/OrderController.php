<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Package;
use App\Models\ProductionStage;
use App\Services\OrderService;
use App\Services\ProductionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $orders = Order::query()
            ->with(['customer', 'package'])
            ->when($search, function ($query, $search) {
                $query->where('kode_order', 'like', "%{$search}%")
                      ->orWhere('nama_team', 'like', "%{$search}%")
                      ->orWhereHas('customer', function ($cq) use ($search) {
                          $cq->where('company_name', 'like', "%{$search}%");
                      });
            })
            ->orderBy('kode_order', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('orders.index', compact('orders', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::orderBy('company_name')->get();
        $packages = Package::where('is_active', true)->orderBy('name')->get();

        if (request()->expectsJson()) {
            return response()->json([
                'customers' => $customers,
                'packages' => $packages,
            ]);
        }

        return view('orders.create', compact('customers', 'packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'nama_team' => 'nullable|string|max:255',
            'bahan' => 'nullable|string|max:255',
            'tanggal_masuk' => 'required|date',
            'deadline' => 'required|date|after_or_equal:tanggal_masuk',
            'package_id' => 'required|exists:packages,id',
            'qty_esti' => 'required|integer|min:0',
            'total_tagihan' => 'required|numeric|min:0',
            'dp' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        try {
            $data = $request->only([
                'customer_id', 'nama_team', 'bahan', 'tanggal_masuk', 
                'deadline', 'package_id', 'qty_esti', 
                'total_tagihan', 'dp', 'catatan'
            ]);

            $order = app(OrderService::class)->createOrder($data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dibuat.',
                    'data' => $order->load('customer', 'package'),
                ], 201);
            }

            return redirect()->route('orders.index')
                ->with('success', 'Pesanan berhasil dibuat.');
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load([
            'customer', 
            'package',
            'players', 
            'designFiles.uploader', 
            'productionLogs' => function ($q) {
                $q->orderBy('started_at', 'desc')->with(['stage', 'updater']);
            }, 
            'invoices.payments',
            'attachments'
        ]);

        $productionStages = ProductionStage::orderBy('sequence')->get();
        $sizes = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

        return view('orders.show', compact('order', 'productionStages', 'sizes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $customers = Customer::orderBy('company_name')->get();
        $packages = Package::where('is_active', true)->orderBy('name')->get();

        if (request()->expectsJson()) {
            return response()->json([
                'order' => $order,
                'customers' => $customers,
                'packages' => $packages,
            ]);
        }

        return view('orders.edit', compact('order', 'customers', 'packages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'nama_team' => 'nullable|string|max:255',
            'bahan' => 'nullable|string|max:255',
            'tanggal_masuk' => 'required|date',
            'deadline' => 'required|date|after_or_equal:tanggal_masuk',
            'package_id' => 'required|exists:packages,id',
            'qty_esti' => 'required|integer|min:0',
            'total_tagihan' => 'required|numeric|min:0',
            'dp' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
            'status_produksi' => 'nullable|string|in:Antrian,Print,Press,Pola,Jahit,Finish,Siap Kirim,Selesai',
        ]);

        try {
            $data = $request->only([
                'customer_id', 'nama_team', 'bahan', 'tanggal_masuk', 
                'deadline', 'package_id', 'qty_esti', 
                'total_tagihan', 'dp', 'catatan'
            ]);

            if ($request->has('status_produksi')) {
                $data['status_produksi'] = $request->input('status_produksi');
            }

            DB::transaction(function () use ($order, $data) {
                $order->update($data);
                app(OrderService::class)->syncInvoiceAndPayment($order);
            });

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil diperbarui.',
                    'data' => $order->load('customer', 'package'),
                ]);
            }

            return redirect()->route('orders.index')
                ->with('success', 'Pesanan berhasil diperbarui.');
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        if ($order->status_produksi === 'Selesai') {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus pesanan yang sudah selesai.',
                ], 422);
            }
            return back()->with('error', 'Tidak dapat menghapus pesanan yang sudah selesai.');
        }

        DB::transaction(function () use ($order) {
            $order->players()->delete();
            $order->designFiles()->delete();
            $order->productionLogs()->delete();
            $order->invoices()->delete();
            $order->delete();
        });

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dihapus.',
            ]);
        }

        return redirect()->route('orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }

    /**
     * Update Player List for specific Order.
     */
    public function updatePlayers(Request $request, Order $order)
    {
        $request->validate([
            'players' => 'nullable|array',
            'players.*.player_name' => 'required|string|max:255',
            'players.*.player_number' => 'nullable|string|max:50',
            'players.*.size' => 'required|string|in:S,M,L,XL,XXL,XXXL',
            'players.*.notes' => 'nullable|string',
        ]);

        try {
            $players = $request->input('players', []);
            app(OrderService::class)->updatePlayerList($order->id, $players);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Personalisasi pemain berhasil diperbarui.',
                ]);
            }

            return back()->with('success', 'Personalisasi pemain berhasil diperbarui.');
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Upload versioned Design File.
     */
    public function uploadDesign(Request $request, Order $order)
    {
        $request->validate([
            'design_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'file_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            if ($request->hasFile('design_file')) {
                $path = $request->file('design_file')->store('designs', 'public');
                
                app(OrderService::class)->uploadDesignFile(
                    $order, 
                    $path, 
                    $request->input('file_name'), 
                    $request->input('notes')
                );

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Berkas desain berhasil diunggah.',
                    ]);
                }

                return back()->with('success', 'Berkas desain berhasil diunggah.');
            }
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil berkas yang diunggah.',
                ], 422);
            }
            return back()->with('error', 'Gagal mengambil berkas yang diunggah.');
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Transition Order to a new production stage.
     */
    public function transitionStage(Request $request, Order $order)
    {
        $request->validate([
            'stage_id' => 'required|exists:production_stages,id',
            'notes' => 'nullable|string',
        ]);

        try {
            app(ProductionService::class)->transitionStage(
                $order->id, 
                $request->input('stage_id'), 
                $request->input('notes')
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tahap produksi berhasil dialihkan.',
                ]);
            }

            return back()->with('success', 'Tahap produksi berhasil dialihkan.');
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Public endpoint to track order production stage.
     */
    public function trackPublic(Request $request)
    {
        $code = $request->input('code');
        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan masukkan nomor pesanan.'
            ], 400);
        }

        $order = Order::with(['productionLogs.stage'])
            ->where('kode_order', trim($code))
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan. Silakan periksa nomor pesanan dan coba lagi.'
            ], 404);
        }

        $stages = ProductionStage::orderBy('sequence')->get();
        $logs = $order->productionLogs;
        
        $activeLog = $logs->firstWhere('completed_at', null);
        if (!$activeLog) {
            $latestLog = $logs->sortByDesc('started_at')->first();
            $currentStageId = $latestLog ? $latestLog->stage_id : null;
        } else {
            $currentStageId = $activeLog->stage_id;
        }
        
        $currentStage = $stages->firstWhere('id', $currentStageId);
        $currentSequence = $currentStage ? $currentStage->sequence : -1;
        $isOrderCompleted = $order->status_produksi === 'Selesai';

        $mappedStages = $stages->map(function ($stage) use ($currentSequence, $currentStageId, $isOrderCompleted) {
            if ($isOrderCompleted) {
                $status = 'completed';
            } else {
                if ($stage->id === $currentStageId) {
                    $status = 'active';
                } elseif ($stage->sequence < $currentSequence) {
                    $status = 'completed';
                } else {
                    $status = 'pending';
                }
            }
            return [
                'name' => $stage->name,
                'status' => $status,
                'description' => $stage->description,
            ];
        });

        return response()->json([
            'success' => true,
            'order' => [
                'order_number' => $order->kode_order,
                'status' => $order->status_produksi,
                'date' => $order->tanggal_masuk ? $order->tanggal_masuk->format('d M Y') : '-',
                'deadline' => $order->deadline ? $order->deadline->format('d M Y') : '-',
            ],
            'stages' => $mappedStages
        ]);
    }
}
