// ============================================================
// SPORTS LEAGUE — Reusable UI Components
// ============================================================
import { useState, useEffect, useRef, ReactNode } from 'react';
import { FieldDef, ValidationError } from './store';

// ---------- ICON ----------
export function Icon({ name, className = '' }: { name: string; className?: string }) {
  return <span className={className}>{name}</span>;
}

// ---------- MODAL ----------
export function Modal({
  open, onClose, title, children, footer
}: {
  open: boolean; onClose: () => void; title: string; children: ReactNode; footer?: ReactNode;
}) {
  useEffect(() => {
    if (open) document.body.style.overflow = 'hidden';
    else document.body.style.overflow = '';
    return () => { document.body.style.overflow = ''; };
  }, [open]);

  if (!open) return null;
  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40" onClick={onClose}>
      <div
        className="w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white rounded-2xl shadow-2xl border border-slate-200"
        onClick={e => e.stopPropagation()}
      >
        <div className="px-6 py-4 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white rounded-t-2xl z-10">
          <h3 className="text-lg font-bold text-slate-800">{title}</h3>
          <button onClick={onClose} className="text-slate-400 hover:text-slate-600 text-2xl leading-none w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100">
            ×
          </button>
        </div>
        <div className="p-6">{children}</div>
        {footer && <div className="px-6 py-4 border-t border-slate-200 flex justify-end gap-3 bg-slate-50 rounded-b-2xl">{footer}</div>}
      </div>
    </div>
  );
}

// ---------- CONFIRM DIALOG ----------
export function ConfirmDialog({
  open, onClose, onConfirm, title, message
}: {
  open: boolean; onClose: () => void; onConfirm: () => void; title: string; message: string;
}) {
  if (!open) return null;
  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40" onClick={onClose}>
      <div className="w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-200" onClick={e => e.stopPropagation()}>
        <div className="p-6">
          <div className="flex items-center gap-3 mb-3">
            <div className="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-500 text-xl">⚠</div>
            <h3 className="text-lg font-bold text-slate-800">{title}</h3>
          </div>
          <p className="text-slate-600 ml-13" style={{ marginLeft: '52px' }}>{message}</p>
        </div>
        <div className="px-6 py-4 border-t border-slate-200 flex justify-end gap-3 bg-slate-50 rounded-b-2xl">
          <button onClick={onClose} className="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition">
            Cancel
          </button>
          <button onClick={() => { onConfirm(); onClose(); }} className="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-600 transition">
            Delete
          </button>
        </div>
      </div>
    </div>
  );
}

// ---------- FIELD ----------
export function Field({
  field, value, onChange, error
}: {
  field: FieldDef; value: any; onChange: (v: any) => void; error?: string;
}) {
  const baseInput = "w-full px-3 py-2.5 text-sm bg-white border rounded-lg transition focus:outline-none focus:ring-2 focus:ring-[#3ECFAB]/30 focus:border-[#3ECFAB]";
  const inputClass = baseInput + (error ? ' border-red-400' : ' border-slate-300 hover:border-slate-400');

  return (
    <div>
      <label className="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">
        {field.label}{field.required && <span className="text-red-500 ml-0.5">*</span>}
      </label>
      {field.type === 'textarea' ? (
        <textarea
          value={value ?? ''}
          onChange={e => onChange(e.target.value)}
          className={inputClass + ' min-h-[80px] resize-y'}
          rows={3}
        />
      ) : field.type === 'select' ? (
        <select
          value={value ?? ''}
          onChange={e => onChange(field.name === 'phone' || field.name.endsWith('_id') ? (e.target.value ? Number(e.target.value) : '') : e.target.value)}
          className={inputClass}
        >
          <option value="">-- Select --</option>
          {field.options?.map(o => (
            <option key={o.value} value={o.value}>{o.label}</option>
          ))}
        </select>
      ) : (
        <input
          type={field.type === 'number' ? 'number' : field.type === 'date' ? 'date' : 'text'}
          value={value ?? ''}
          onChange={e => onChange(field.type === 'number' ? (e.target.value === '' ? '' : Number(e.target.value)) : e.target.value)}
          className={inputClass}
        />
      )}
      {error && <p className="mt-1 text-xs text-red-500 font-medium">{error}</p>}
    </div>
  );
}

// ---------- TOAST CONTAINER ----------
export function ToastContainer({ toasts }: { toasts: { id: number; type: 'success' | 'error' | 'info'; text: string; }[] }) {
  return (
    <div className="fixed top-4 right-4 z-[100] flex flex-col gap-2 pointer-events-none">
      {toasts.map(t => {
        const color = t.type === 'success' ? 'bg-emerald-500' : t.type === 'error' ? 'bg-red-500' : 'bg-blue-500';
        const icon = t.type === 'success' ? '✓' : t.type === 'error' ? '✕' : 'ℹ';
        return (
          <div key={t.id} className={`${color} text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-2.5 min-w-[280px] pointer-events-auto animate-slide-in`}>
            <span className="w-6 h-6 rounded-full bg-white/25 flex items-center justify-center text-sm font-bold flex-shrink-0">{icon}</span>
            <span className="text-sm font-medium">{t.text}</span>
          </div>
        );
      })}
    </div>
  );
}

// ---------- CRUD TABLE ----------
export function CRUDTable({
  module, columns, rows, onAdd, onEdit, onDelete, search, onSearch, resolve, db
}: {
  module: { key: string; label: string; singular: string; icon: string; fields: FieldDef[] };
  columns: { key: string; label: string; width?: string }[];
  rows: any[];
  onAdd: () => void;
  onEdit: (r: any) => void;
  onDelete: (r: any) => void;
  search: string;
  onSearch: (s: string) => void;
  resolve: (key: string, id: number, db: any) => string;
  db: any;
}) {
  const [confirmRec, setConfirmRec] = useState<any>(null);

  return (
    <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
      <div className="px-6 py-4 border-b border-slate-200 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
        <div>
          <h2 className="text-xl font-bold text-slate-800 flex items-center gap-2">
            <span className="text-2xl">{module.icon}</span>
            {module.label}
            <span className="text-xs font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">{rows.length}</span>
          </h2>
        </div>
        <div className="flex items-center gap-3">
          <div className="relative">
            <svg className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <input
              type="text"
              placeholder="Search…"
              value={search}
              onChange={e => onSearch(e.target.value)}
              className="pl-9 pr-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3ECFAB]/30 focus:border-[#3ECFAB] w-48 sm:w-64"
            />
          </div>
          <button onClick={onAdd} className="px-4 py-2 bg-[#3ECFAB] hover:bg-[#35b897] text-white text-sm font-semibold rounded-lg shadow-sm transition flex items-center gap-1.5 whitespace-nowrap">
            <span className="text-lg leading-none">+</span> Add {module.singular}
          </button>
        </div>
      </div>

      <div className="overflow-x-auto">
        <table className="w-full text-sm">
          <thead className="bg-slate-50 border-b border-slate-200">
            <tr>
              {columns.map(c => (
                <th key={c.key} style={{ width: c.width }} className="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                  {c.label}
                </th>
              ))}
              <th className="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider w-28">Actions</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-100">
            {rows.length === 0 ? (
              <tr><td colSpan={columns.length + 1} className="px-4 py-12 text-center text-slate-400">No records found</td></tr>
            ) : rows.map(r => (
              <tr key={r.id} className="hover:bg-slate-50/50 transition">
                {columns.map(c => {
                  let val = r[c.key];
                  // FK resolution
                  if (c.key.endsWith('_id') && c.key !== 'id') {
                    val = resolve(c.key, val, db);
                  }
                  return (
                    <td key={c.key} className="px-4 py-3 text-slate-700 font-medium whitespace-nowrap">
                      {c.key === 'status' ? (
                        <span className={`inline-block px-2 py-0.5 rounded-full text-xs font-semibold ${
                          val === 'Active' || val === 'Completed' ? 'bg-emerald-100 text-emerald-700' :
                          val === 'Upcoming' || val === 'Scheduled' ? 'bg-blue-100 text-blue-700' :
                          val === 'Postponed' ? 'bg-amber-100 text-amber-700' :
                          val === 'Cancelled' ? 'bg-red-100 text-red-700' :
                          val === 'Live' ? 'bg-red-100 text-red-600 animate-pulse' :
                          'bg-slate-100 text-slate-700'
                        }`}>{val}</span>
                      ) : c.key === 'severity' ? (
                        <span className={`inline-block px-2 py-0.5 rounded-full text-xs font-semibold ${
                          val === 'Mild' ? 'bg-emerald-100 text-emerald-700' :
                          val === 'Moderate' ? 'bg-amber-100 text-amber-700' :
                          val === 'Severe' ? 'bg-red-100 text-red-700' :
                          'bg-red-200 text-red-800'
                        }`}>{val}</span>
                      ) : c.key === 'condition_status' ? (
                        <span className={`inline-block px-2 py-0.5 rounded-full text-xs font-semibold ${
                          val === 'New' ? 'bg-emerald-100 text-emerald-700' :
                          val === 'Good' ? 'bg-blue-100 text-blue-700' :
                          val === 'Fair' ? 'bg-amber-100 text-amber-700' :
                          'bg-red-100 text-red-700'
                        }`}>{val}</span>
                      ) : c.key === 'amount' || c.key === 'price' ? (
                        val !== undefined && val !== '' ? `$${Number(val).toLocaleString()}` : ''
                      ) : (
                        String(val ?? '')
                      )}
                    </td>
                  );
                })}
                <td className="px-4 py-3 text-right whitespace-nowrap">
                  <button onClick={() => onEdit(r)} className="text-slate-400 hover:text-[#3ECFAB] p-1.5 rounded-lg hover:bg-emerald-50 transition mr-1" title="Edit">
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                  </button>
                  <button onClick={() => setConfirmRec(r)} className="text-slate-400 hover:text-red-500 p-1.5 rounded-lg hover:bg-red-50 transition" title="Delete">
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      <ConfirmDialog
        open={!!confirmRec}
        onClose={() => setConfirmRec(null)}
        onConfirm={() => { onDelete(confirmRec); setConfirmRec(null); }}
        title="Delete Record"
        message={`Are you sure you want to delete this ${module.singular.toLowerCase()}? This action cannot be undone.`}
      />
    </div>
  );
}

// ---------- FORM MODAL ----------
export function FormModal({
  open, onClose, title, fields, record, onSave, initialErrors
}: {
  open: boolean; onClose: () => void; title: string; fields: FieldDef[]; record: any; onSave: (rec: any) => void; initialErrors?: ValidationError;
}) {
  const [form, setForm] = useState<any>({});
  const [errors, setErrors] = useState<ValidationError>({});
  const firstInputRef = useRef<HTMLInputElement>(null);

  useEffect(() => {
    if (open) {
      // reset form to record (or blank)
      const init: any = {};
      fields.forEach(f => {
        init[f.name] = record ? record[f.name] ?? '' : '';
      });
      setForm(init);
      setErrors(initialErrors || {});
      setTimeout(() => firstInputRef.current?.focus(), 100);
    }
  }, [open, record, fields, initialErrors]);

  const setField = (name: string, val: any) => {
    setForm((p: any) => ({ ...p, [name]: val }));
    setErrors((p) => { const n = { ...p }; delete n[name]; return n; });
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSave(form);
  };

  return (
    <Modal
      open={open}
      onClose={onClose}
      title={title}
      footer={
        <>
          <button onClick={onClose} className="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition">
            Cancel
          </button>
          <button type="submit" form="crud-form" className="px-5 py-2 text-sm font-semibold text-white bg-[#3ECFAB] rounded-lg hover:bg-[#35b897] transition shadow-sm">
            Save
          </button>
        </>
      }
    >
      <form id="crud-form" onSubmit={handleSubmit} className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {fields.map((f, idx) => (
          <div key={f.name} className={f.type === 'textarea' ? 'sm:col-span-2' : ''}>
            <label className="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">
              {f.label}{f.required && <span className="text-red-500 ml-0.5">*</span>}
            </label>
            {f.type === 'textarea' ? (
              <textarea
                ref={idx === 0 ? firstInputRef as any : undefined}
                value={form[f.name] ?? ''}
                onChange={e => setField(f.name, e.target.value)}
                className={`w-full px-3 py-2.5 text-sm bg-white border rounded-lg transition focus:outline-none focus:ring-2 focus:ring-[#3ECFAB]/30 ${errors[f.name] ? 'border-red-400' : 'border-slate-300 focus:border-[#3ECFAB]'}`}
                rows={3}
              />
            ) : f.type === 'select' ? (
              <select
                value={form[f.name] ?? ''}
                onChange={e => setField(f.name, e.target.value === '' ? '' : (f.name.endsWith('_id') ? Number(e.target.value) : e.target.value))}
                className={`w-full px-3 py-2.5 text-sm bg-white border rounded-lg transition focus:outline-none focus:ring-2 focus:ring-[#3ECFAB]/30 ${errors[f.name] ? 'border-red-400' : 'border-slate-300 focus:border-[#3ECFAB]'}`}
              >
                <option value="">-- Select --</option>
                {f.options?.map(o => (
                  <option key={o.value} value={o.value}>{o.label}</option>
                ))}
              </select>
            ) : (
              <input
                ref={idx === 0 ? firstInputRef : undefined}
                type={f.type === 'number' ? 'number' : f.type === 'date' ? 'date' : 'text'}
                value={form[f.name] ?? ''}
                onChange={e => setField(f.name, f.type === 'number' ? (e.target.value === '' ? '' : Number(e.target.value)) : e.target.value)}
                className={`w-full px-3 py-2.5 text-sm bg-white border rounded-lg transition focus:outline-none focus:ring-2 focus:ring-[#3ECFAB]/30 ${errors[f.name] ? 'border-red-400' : 'border-slate-300 focus:border-[#3ECFAB]'}`}
              />
            )}
            {errors[f.name] && <p className="mt-1 text-xs text-red-500 font-medium">{errors[f.name]}</p>}
          </div>
        ))}
      </form>
    </Modal>
  );
}
