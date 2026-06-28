<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class CustomOrderController extends Controller
{
    /**
     * Show the custom order submission form
     */
    public function showForm()
    {
        return view('custom-order.form');
    }

    /**
     * Handle the custom order submission
     */
    public function submitForm(Request $request)
    {
        $request->validate([
            'nama_pemesan' => 'required|string|max:255',
            'no_wa' => 'required|string|max:50',
            'nama_pesanan' => 'required|string|max:255',
            'jenis_apparel' => 'required|string|max:255',
            'jenis_apparel_lainnya' => 'nullable|required_if:jenis_apparel,Lainnya|string|max:255',
            'jumlah_pesanan' => 'required|integer|min:1',
            'target_penggunaan' => 'nullable|date|after_or_equal:today',
            'budget_perkiraan' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'kota_kabupaten' => 'nullable|string|max:255',
            'instansi_komunitas' => 'nullable|string|max:255',
            'catatan_kebutuhan' => 'nullable|string',
            
            // File uploads validation
            'logo_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,ai,cdr|max:10240',
            'design_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,ai,cdr|max:10240',
            'color_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,ai,cdr|max:10240',
            'supporting_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,ai,cdr|max:10240',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // 1. Find or create customer
                $customer = Customer::where('phone', $request->no_wa)
                    ->when($request->email, function ($q, $email) {
                        $q->orWhere('email', $email);
                    })
                    ->first();

                if (!$customer) {
                    $customer = Customer::create([
                        'company_name' => $request->instansi_komunitas ?: ('Personal - ' . $request->nama_pemesan),
                        'contact_person' => $request->nama_pemesan,
                        'phone' => $request->no_wa,
                        'email' => $request->email,
                        'city' => $request->kota_kabupaten,
                        'address' => '-',
                    ]);
                }

                // 2. Generate unique order code (ORD-YYYYMM-XXXX)
                $prefix = 'ORD-' . date('Ym') . '-';
                $latestOrder = Order::where('kode_order', 'like', $prefix . '%')
                    ->orderBy('kode_order', 'desc')
                    ->first();

                if ($latestOrder) {
                    $parts = explode('-', $latestOrder->kode_order);
                    $seq = intval(end($parts)) + 1;
                } else {
                    $seq = 1;
                }
                $kodeOrder = $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);

                // 3. Process budget numeric conversion
                $budget = null;
                if ($request->budget_perkiraan) {
                    $budget = floatval(preg_replace('/[^0-9]/', '', $request->budget_perkiraan));
                }

                // 4. Create Order
                $jenisApparel = $request->jenis_apparel;
                if ($jenisApparel === 'Lainnya') {
                    $jenisApparel = $request->jenis_apparel_lainnya;
                }

                $order = Order::create([
                    'kode_order' => $kodeOrder,
                    'customer_id' => $customer->id,
                    'nama_team' => $request->nama_pesanan,
                    'bahan' => '-',
                    'tanggal_masuk' => now()->toDateString(),
                    'deadline' => $request->target_penggunaan ?: now()->addDays(14)->toDateString(),
                    'qty_esti' => $request->jumlah_pesanan,
                    'qty_tink' => 0,
                    'total_tagihan' => 0.00,
                    'dp' => 0.00,
                    'sisa_tagihan' => 0.00,
                    'status_pembayaran' => 'Belum DP',
                    'status_produksi' => 'Menunggu Review',
                    'catatan' => $request->catatan_kebutuhan,
                    'source' => 'Landing Page',
                    'jenis_apparel' => $jenisApparel,
                    'target_penggunaan' => $request->target_penggunaan,
                    'budget_perkiraan' => $budget,
                ]);

                // 5. Handle file attachments
                $fileCategories = [
                    'logo_files' => 'logo',
                    'design_files' => 'design_reference',
                    'color_files' => 'color_reference',
                    'supporting_files' => 'supporting_file',
                ];

                foreach ($fileCategories as $inputKey => $fileType) {
                    if ($request->hasFile($inputKey)) {
                        foreach ($request->file($inputKey) as $file) {
                            if ($file->isValid()) {
                                $path = $file->store('attachments', 'public');
                                OrderAttachment::create([
                                    'order_id' => $order->id,
                                    'file_name' => $file->getClientOriginalName(),
                                    'file_path' => $path,
                                    'file_type' => $fileType,
                                    'file_size' => $file->getSize(),
                                ]);
                            }
                        }
                    }
                }

                return redirect()->route('custom-order.success')->with([
                    'success_order_code' => $order->kode_order,
                    'success_nama_pemesan' => $request->nama_pemesan,
                ]);
            });
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Show success page
     */
    public function success()
    {
        $orderCode = session('success_order_code');
        $namaPemesan = session('success_nama_pemesan');

        if (!$orderCode) {
            return redirect()->route('custom-order.form');
        }

        // Generate WhatsApp message
        $waNumber = \App\Models\Setting::getValue('whatsapp_number', '6281234567890');
        $waMessage = "Halo Admin,\n\nSaya telah mengirim formulir pemesanan custom apparel.\n\nNomor Pesanan:\n{$orderCode}\n\nNama:\n{$namaPemesan}\n\nMohon informasi tahap selanjutnya.";
        $waUrl = "https://wa.me/" . trim($waNumber) . "?text=" . urlencode($waMessage);

        return view('custom-order.success', compact('orderCode', 'namaPemesan', 'waUrl'));
    }
}
