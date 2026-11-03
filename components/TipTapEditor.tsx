'use client';

import React from 'react';
import { useEditor, EditorContent } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import { 
  Bold, 
  Italic, 
  Strikethrough, 
  Heading1, 
  Heading2, 
  List, 
  ListOrdered, 
  Quote, 
  Undo, 
  Redo 
} from 'lucide-react';

interface TipTapEditorProps {
  content: string;
  onChange: (html: string) => void;
  placeholder?: string;
  editable?: boolean;
}

export default function TipTapEditor({
  content,
  onChange,
  placeholder = 'Tulis catatan atau instruksi spesifikasi produksi...',
  editable = true,
}: TipTapEditorProps) {
  const editor = useEditor({
    extensions: [StarterKit],
    content: content || `<p>${placeholder}</p>`,
    editable,
    onUpdate: ({ editor }) => {
      onChange(editor.getHTML());
    },
  });

  if (!editor) {
    return <div className="p-4 bg-slate-50 border rounded-lg text-xs text-slate-400">Memuat editor catatan...</div>;
  }

  return (
    <div className="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-sm focus-within:ring-2 focus-within:ring-blue-500/20 focus-within:border-blue-500 transition">
      {/* Editor Toolbar */}
      {editable && (
        <div className="bg-slate-50 px-3 py-2 border-b border-slate-200 flex flex-wrap items-center gap-1">
          <button
            type="button"
            onClick={() => editor.chain().focus().toggleBold().run()}
            className={`p-1.5 rounded hover:bg-slate-200/80 transition ${
              editor.isActive('bold') ? 'bg-slate-200 text-blue-700 font-bold' : 'text-slate-600'
            }`}
            title="Tebal"
          >
            <Bold className="w-4 h-4" />
          </button>
          <button
            type="button"
            onClick={() => editor.chain().focus().toggleItalic().run()}
            className={`p-1.5 rounded hover:bg-slate-200/80 transition ${
              editor.isActive('italic') ? 'bg-slate-200 text-blue-700' : 'text-slate-600'
            }`}
            title="Miring"
          >
            <Italic className="w-4 h-4" />
          </button>
          <button
            type="button"
            onClick={() => editor.chain().focus().toggleStrike().run()}
            className={`p-1.5 rounded hover:bg-slate-200/80 transition ${
              editor.isActive('strike') ? 'bg-slate-200 text-blue-700' : 'text-slate-600'
            }`}
            title="Coret"
          >
            <Strikethrough className="w-4 h-4" />
          </button>

          <div className="w-px h-5 bg-slate-300 mx-1" />

          <button
            type="button"
            onClick={() => editor.chain().focus().toggleHeading({ level: 1 }).run()}
            className={`p-1.5 rounded hover:bg-slate-200/80 transition text-xs font-bold ${
              editor.isActive('heading', { level: 1 }) ? 'bg-slate-200 text-blue-700' : 'text-slate-600'
            }`}
            title="Judul Besar"
          >
            <Heading1 className="w-4 h-4" />
          </button>
          <button
            type="button"
            onClick={() => editor.chain().focus().toggleHeading({ level: 2 }).run()}
            className={`p-1.5 rounded hover:bg-slate-200/80 transition text-xs font-bold ${
              editor.isActive('heading', { level: 2 }) ? 'bg-slate-200 text-blue-700' : 'text-slate-600'
            }`}
            title="Sub Judul"
          >
            <Heading2 className="w-4 h-4" />
          </button>

          <div className="w-px h-5 bg-slate-300 mx-1" />

          <button
            type="button"
            onClick={() => editor.chain().focus().toggleBulletList().run()}
            className={`p-1.5 rounded hover:bg-slate-200/80 transition ${
              editor.isActive('bulletList') ? 'bg-slate-200 text-blue-700' : 'text-slate-600'
            }`}
            title="Daftar Bullet"
          >
            <List className="w-4 h-4" />
          </button>
          <button
            type="button"
            onClick={() => editor.chain().focus().toggleOrderedList().run()}
            className={`p-1.5 rounded hover:bg-slate-200/80 transition ${
              editor.isActive('orderedList') ? 'bg-slate-200 text-blue-700' : 'text-slate-600'
            }`}
            title="Daftar Angka"
          >
            <ListOrdered className="w-4 h-4" />
          </button>
          <button
            type="button"
            onClick={() => editor.chain().focus().toggleBlockquote().run()}
            className={`p-1.5 rounded hover:bg-slate-200/80 transition ${
              editor.isActive('blockquote') ? 'bg-slate-200 text-blue-700' : 'text-slate-600'
            }`}
            title="Kutipan"
          >
            <Quote className="w-4 h-4" />
          </button>

          <div className="w-px h-5 bg-slate-300 mx-1" />

          <button
            type="button"
            onClick={() => editor.chain().focus().undo().run()}
            disabled={!editor.can().undo()}
            className="p-1.5 rounded text-slate-500 hover:bg-slate-200/80 disabled:opacity-40 transition"
            title="Undo"
          >
            <Undo className="w-4 h-4" />
          </button>
          <button
            type="button"
            onClick={() => editor.chain().focus().redo().run()}
            disabled={!editor.can().redo()}
            className="p-1.5 rounded text-slate-500 hover:bg-slate-200/80 disabled:opacity-40 transition"
            title="Redo"
          >
            <Redo className="w-4 h-4" />
          </button>
        </div>
      )}

      {/* Editable Area */}
      <div className="p-4 text-sm text-slate-800 leading-relaxed min-h-[220px]">
        <EditorContent editor={editor} />
      </div>
    </div>
  );
}
