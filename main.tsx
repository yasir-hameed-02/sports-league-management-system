// ============================================================
// SPORTS LEAGUE MANAGEMENT SYSTEM — Main App
// ============================================================
import { useState, useMemo } from 'react';
import { useDB, validateRecord, extraValidation } from './store';
import type { DB, FieldDef } from './store';
import { buildModules, resolveFK, ModuleDef } from './schema';
import { CRUDTable, FormModal, ToastContainer } from './components';

type TableKey = keyof DB;

function App() {
  const { db, create, update, remove, reset, toasts, totalRecords, moduleCount } = useDB();
  const [route, setRoute] = useState<string>('dashboard');
  const [search, setSearch] = useState('');
  const [modalOpen, setModalOpen] = useState(false);
  const [editingRec, setEditingRec] = useState<any>(null);
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const modules = useMemo(() => buildModules(db), [db]);
  const currentModule: ModuleDef | undefined = useMemo(
    () => modules.find(m => m.key === route),
    [modules, route]
  );

  const filteredRows = useMemo(() => {
  if (!currentModule) return [];
  const rows = (db as any)[currentModule.key] as any[];
  if (!search.trim()) return rows;
  const q = search.toLowerCase();
  return rows.filter(r => {
    // Check raw values
    const rawMatch = Object.values(r).some(v =>
      String(v ?? '').toLowerCase().includes(q)
    );
    if (rawMatch) return true;
    // Check resolved FK values (team names, player names, etc.)
    return currentModule.columns.some(col => {
      const val = r[col.key];
      if (typeof val === 'number') {
        const resolved = resolveFK(col.key, val, db);
        return resolved.toLowerCase().includes(q);
      }
      return false;
    });
  });
}, [currentModule, db, search]);

  const handleAdd = () => { setEditingRec(null); setModalOpen(true); };
  const handleEdit = (r: any) => { setEditingRec(r); setModalOpen(true); };
  const handleDelete = (r: any) => {
    if (currentModule) remove(currentModule.key as TableKey, r.id);
  };

  const handleSave = (form: any) => {
    if (!currentModule) return;
    // Remove empty string values for optional number fields to avoid NaN in validation
    const clean: any = {};
    for (const f of currentModule.fields) {
      let v = form[f.name];
      if (f.type === 'number' && v === '') v = 0;
      clean[f.name] = v;
    }
    if (editingRec) clean.id = editingRec.id;

    const errs1 = validateRecord(currentModule.key as TableKey, clean, currentModule.fields as FieldDef[], db);
    const errs2 = extraValidation(currentModule.key as TableKey, clean, db);
    const allErrors = { ...errs1, ...errs2 };

    if (Object.keys(allErrors).length > 0) {
      // Re-open the form with the data and errors
      // We handle this by showing errors inline via a re-render of FormModal
      // Workaround: trigger the modal to reopen with prefilled errors
      setModalOpen(false);
      setTimeout(() => {
        setEditingRec(clean);
        setFormErrors(allErrors);
        setModalOpen(true);
      }, 50);
      return;
    }

    if (editingRec) {
      update(currentModule.key as TableKey, editingRec.id, clean);
    } else {
      const { id, ...rest } = clean;
      create(currentModule.key as TableKey, rest);
    }
    setModalOpen(false);
    setEditingRec(null);
    setFormErrors({});
  };

  // Temporary form errors state (for re-display after validation fail)
  const [formErrors, setFormErrors] = useState<Record<string, string>>({});

  // When modal closes, clear errors
  const closeModal = () => { setModalOpen(false); setEditingRec(null); setFormErrors({}); };

  const goTo = (r: string) => { setRoute(r); setSearch(''); setSidebarOpen(false); };

  return (
    <div className="min-h-screen bg-[#F8FAFB] text-[#1E293B] flex">
      {/* Mobile overlay */}
      {sidebarOpen && (
        <div className="fixed inset-0 bg-slate-900/40 z-30 lg:hidden" onClick={() => setSidebarOpen(false)} />
      )}

      {/* SIDEBAR */}
      <aside className={`fixed lg:sticky top-0 left-0 h-screen w-64 bg-white border-r border-slate-200 z-40 flex flex-col transition-transform ${sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'}`}>
        {/* Logo */}
        <div className="px-5 py-5 border-b border-slate-100">
          <div className="flex items-center gap-2.5">
            <div className="w-9 h-9 rounded-xl bg-gradient-to-br from-[#3ECFAB] to-[#2BA889] flex items-center justify-center text-white font-bold text-lg shadow-sm">
              🏆
            </div>
            <div>
              <div className="text-sm font-bold text-slate-800 leading-tight">Sports League</div>
              <div className="text-[10px] text-slate-400 uppercase tracking-wider font-medium">Management</div>
            </div>
          </div>
        </div>

        <nav className="flex-1 overflow-y-auto px-3 py-4">
          <button
            onClick={() => goTo('dashboard')}
            className={`w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium mb-1 transition ${
              route === 'dashboard'
                ? 'bg-gradient-to-r from-[#3ECFAB] to-[#2BA889] text-white shadow-sm shadow-emerald-200'
                : 'text-slate-600 hover:bg-slate-50'
            }`}
          >
            <span className="text-base">📊</span>
            Dashboard
          </button>

          <div className="mt-5 mb-2 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Modules</div>

          <div className="space-y-0.5">
            {modules.map(m => {
              const active = route === m.key;
              return (
                <button
                  key={m.key}
                  onClick={() => goTo(m.key)}
                  className={`w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition relative ${
                    active
                      ? 'bg-gradient-to-r from-[#3ECFAB] to-[#2BA889] text-white shadow-sm shadow-emerald-100'
                      : 'text-slate-600 hover:bg-slate-50'
                  }`}
                >
                  {active && <span className="absolute left-0 top-1 bottom-1 w-0.5 bg-white rounded-r" />}
                  <span className="text-base w-5 text-center">{m.icon}</span>
                  <span className="truncate">{m.label}</span>
                  <span className={`ml-auto text-[10px] font-semibold px-1.5 py-0.5 rounded-md ${
                    active ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-400'
                  }`}>
                    {(db as any)[m.key]?.length ?? 0}
                  </span>
                </button>
              );
            })}
          </div>
        </nav>

        {/* Bottom status */}
        <div className="px-4 py-3 border-t border-slate-100">
          <button
            onClick={() => { if (confirm('Reset database to seed data? This will erase all changes.')) reset(); }}
            className="w-full text-xs text-slate-400 hover:text-red-500 transition flex items-center justify-center gap-1.5 mb-2"
          >
            <span>🔄</span> Reset Database
          </button>
          <div className="flex items-center gap-2 px-1">
            <span className="relative flex h-2 w-2">
              <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#3ECFAB] opacity-75"></span>
              <span className="relative inline-flex rounded-full h-2 w-2 bg-[#3ECFAB]"></span>
            </span>
            <div className="text-[11px] text-slate-500">
              <span className="font-semibold text-slate-700">{totalRecords()}</span> records across <span className="font-semibold text-slate-700">{moduleCount}</span> modules
            </div>
          </div>
        </div>
      </aside>

      {/* MAIN */}
      <main className="flex-1 min-w-0 flex flex-col">
        {/* Top bar */}
        <header className="sticky top-0 z-20 bg-white/80 backdrop-blur border-b border-slate-200 px-4 sm:px-8 py-3 flex items-center justify-between">
          <div className="flex items-center gap-3">
            <button
              onClick={() => setSidebarOpen(true)}
              className="lg:hidden text-slate-600 hover:bg-slate-100 p-2 rounded-lg"
            >
              <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
            <h1 className="text-lg font-bold text-slate-800 capitalize">
              {route === 'dashboard' ? 'Dashboard' : currentModule?.label ?? ''}
            </h1>
          </div>
          <div className="flex items-center gap-2">
            <div className="hidden sm:flex items-center gap-2 text-xs text-slate-500 bg-slate-50 px-3 py-1.5 rounded-full border border-slate-200">
              <span>🟢</span> Connected
            </div>
          </div>
        </header>

        <div className="flex-1 p-4 sm:p-8">
          {route === 'dashboard' ? (
            <Dashboard db={db} modules={modules} onNav={goTo} />
          ) : currentModule ? (
            <CRUDTable
              module={currentModule}
              columns={currentModule.columns}
              rows={filteredRows}
              onAdd={handleAdd}
              onEdit={handleEdit}
              onDelete={handleDelete}
              search={search}
              onSearch={setSearch}
              resolve={resolveFK}
              db={db}
            />
          ) : null}
        </div>
      </main>

      {/* Form Modal */}
      {currentModule && (
        <FormModal
          open={modalOpen}
          onClose={closeModal}
          title={editingRec ? `Edit ${currentModule.singular}` : `Add ${currentModule.singular}`}
          fields={currentModule.fields as FieldDef[]}
          record={editingRec}
          onSave={handleSave}
          initialErrors={Object.keys(formErrors).length > 0 ? formErrors : undefined}
        />
      )}

      <ToastContainer toasts={toasts} />
    </div>
  );
}

// ============================================================
// DASHBOARD
// ============================================================
function Dashboard({ db, modules, onNav }: { db: DB; modules: ModuleDef[]; onNav: (r: string) => void }) {
  // Recent matches (last 5)
  const recentMatches = useMemo(() => {
    return [...db.matches].sort((a, b) => b.match_date.localeCompare(a.match_date)).slice(0, 5);
  }, [db.matches]);

  // Top standings by points (active or most recent season)
  const topStandings = useMemo(() => {
    // find most-recent season
    const sortedSeasons = [...db.seasons].sort((a, b) => b.year - a.year);
    const activeSeason = sortedSeasons.find(s => s.status === 'Active') || sortedSeasons[0];
    if (!activeSeason) return [];
    return [...db.standings]
      .filter(s => s.season_id === activeSeason.id)
      .sort((a, b) => b.points - a.points)
      .slice(0, 5);
  }, [db.standings, db.seasons]);

  // Top scorers
  const topScorers = useMemo(() => {
    return [...db.statistics].sort((a, b) => b.goals - a.goals).slice(0, 5);
  }, [db.statistics]);

  const maxPoints = topStandings[0]?.points || 1;
  const maxGoals = topScorers[0]?.goals || 1;

  return (
    <div className="space-y-6">
      {/* Hero */}
      <div className="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#3ECFAB] via-[#2BA889] to-[#1f8a70] p-8 sm:p-10 text-white shadow-lg">
        <div className="relative z-10 max-w-2xl">
          <div className="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur px-3 py-1 rounded-full text-xs font-semibold mb-4">
            <span>⚡</span> Season 2025 Live
          </div>
          <h1 className="text-3xl sm:text-4xl font-black mb-2 leading-tight">Sports League Management</h1>
          <p className="text-white/85 text-base sm:text-lg">
            Manage teams, players, matches, tickets and everything in between — all in one place.
          </p>
        </div>
        <div className="absolute -right-8 -bottom-8 text-[180px] opacity-15 select-none">🏆</div>
      </div>

      {/* Stat cards */}
      <div>
        <h2 className="text-sm font-bold text-slate-500 uppercase tracking-wider mb-3">Module Overview</h2>
        <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
          {modules.map(m => {
            const count = (db as any)[m.key]?.length ?? 0;
            return (
              <button
                key={m.key}
                onClick={() => onNav(m.key)}
                className="bg-white rounded-xl border border-slate-200 p-4 text-left hover:border-[#3ECFAB] hover:shadow-md hover:-translate-y-0.5 transition-all group"
              >
                <div className="flex items-center justify-between mb-2">
                  <span className="text-2xl">{m.icon}</span>
                  <span className="text-xs font-bold text-slate-400 group-hover:text-[#3ECFAB] transition">#{modules.indexOf(m) + 1}</span>
                </div>
                <div className="text-2xl font-black text-slate-800">{count}</div>
                <div className="text-xs font-medium text-slate-500 mt-0.5">{m.label}</div>
              </button>
            );
          })}
        </div>
      </div>

      {/* 3 panels */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {/* Recent Matches */}
        <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
          <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 className="font-bold text-slate-800 flex items-center gap-2"><span>🏆</span> Recent Matches</h3>
            <button onClick={() => onNav('matches')} className="text-xs text-[#3ECFAB] font-semibold hover:underline">View all →</button>
          </div>
          <div className="divide-y divide-slate-100">
            {recentMatches.length === 0 ? (
              <div className="px-5 py-8 text-center text-slate-400 text-sm">No matches yet</div>
            ) : recentMatches.map(m => {
              const home = db.teams.find(t => t.id === m.home_team_id);
              const away = db.teams.find(t => t.id === m.away_team_id);
              return (
                <div key={m.id} className="px-5 py-3.5 flex items-center gap-3 hover:bg-slate-50 transition">
                  <div className="text-xs text-slate-400 w-20 flex-shrink-0">{m.match_date}</div>
                  <div className="flex-1 min-w-0">
                    <div className="text-sm font-semibold text-slate-800 truncate">
                      {home?.name ?? '—'} <span className="text-slate-400 font-normal">vs</span> {away?.name ?? '—'}
                    </div>
                    <div className="text-xs text-slate-400">Attendance: {m.attendance.toLocaleString()}</div>
                  </div>
                  <span className={`text-[10px] font-bold px-2 py-1 rounded-full ${
                    m.status === 'Completed' ? 'bg-emerald-100 text-emerald-700' :
                    m.status === 'Scheduled' ? 'bg-blue-100 text-blue-700' :
                    m.status === 'Live' ? 'bg-red-100 text-red-600 animate-pulse' :
                    'bg-slate-100 text-slate-600'
                  }`}>{m.status}</span>
                </div>
              );
            })}
          </div>
        </div>

        {/* Top Standings */}
        <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
          <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 className="font-bold text-slate-800 flex items-center gap-2"><span>📊</span> Top Standings</h3>
            <button onClick={() => onNav('standings')} className="text-xs text-[#3ECFAB] font-semibold hover:underline">View all →</button>
          </div>
          <div className="divide-y divide-slate-100">
            {topStandings.length === 0 ? (
              <div className="px-5 py-8 text-center text-slate-400 text-sm">No standings data</div>
            ) : topStandings.map((s, idx) => {
              const team = db.teams.find(t => t.id === s.team_id);
              const pct = (s.points / maxPoints) * 100;
              return (
                <div key={s.id} className="px-5 py-3.5 hover:bg-slate-50 transition">
                  <div className="flex items-center gap-3 mb-1.5">
                    <span className={`w-6 h-6 rounded-md flex items-center justify-center text-[11px] font-bold ${
                      idx === 0 ? 'bg-amber-100 text-amber-700' :
                      idx === 1 ? 'bg-slate-200 text-slate-600' :
                      idx === 2 ? 'bg-orange-100 text-orange-700' :
                      'bg-slate-100 text-slate-500'
                    }`}>{idx + 1}</span>
                    <span className="flex-1 text-sm font-semibold text-slate-800 truncate">{team?.name ?? '—'}</span>
                    <span className="text-sm font-black text-slate-700">{s.points} pts</span>
                  </div>
                  <div className="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div className="h-full bg-gradient-to-r from-[#3ECFAB] to-[#2BA889] rounded-full transition-all" style={{ width: `${pct}%` }} />
                  </div>
                </div>
              );
            })}
          </div>
        </div>

        {/* Top Scorers */}
        <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
          <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 className="font-bold text-slate-800 flex items-center gap-2"><span>⚽</span> Top Scorers</h3>
            <button onClick={() => onNav('statistics')} className="text-xs text-[#3ECFAB] font-semibold hover:underline">View all →</button>
          </div>
          <div className="divide-y divide-slate-100">
            {topScorers.length === 0 ? (
              <div className="px-5 py-8 text-center text-slate-400 text-sm">No statistics</div>
            ) : topScorers.map((s, idx) => {
              const player = db.players.find(p => p.id === s.player_id);
              const pct = (s.goals / maxGoals) * 100;
              return (
                <div key={s.id} className="px-5 py-3.5 hover:bg-slate-50 transition">
                  <div className="flex items-center gap-3 mb-1.5">
                    <span className={`w-6 h-6 rounded-md flex items-center justify-center text-[11px] font-bold ${
                      idx === 0 ? 'bg-amber-100 text-amber-700' :
                      idx === 1 ? 'bg-slate-200 text-slate-600' :
                      idx === 2 ? 'bg-orange-100 text-orange-700' :
                      'bg-slate-100 text-slate-500'
                    }`}>{idx + 1}</span>
                    <span className="flex-1 text-sm font-semibold text-slate-800 truncate">{player?.name ?? '—'}</span>
                    <span className="text-sm font-black text-slate-700">{s.goals} G</span>
                  </div>
                  <div className="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div className="h-full bg-gradient-to-r from-[#F59E0B] to-[#D97706] rounded-full transition-all" style={{ width: `${pct}%` }} />
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      </div>

      {/* Feature highlights */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-3">
        {[
          { icon: '🗂️', title: '20+ Tables', desc: 'Complete league model' },
          { icon: '⚙️', title: 'Full CRUD', desc: 'Create, read, update, delete' },
          { icon: '🔗', title: 'JOIN Queries', desc: 'Relational data intact' },
          { icon: '✅', title: 'Validation', desc: 'Business rules enforced' },
        ].map(f => (
          <div key={f.title} className="bg-white rounded-xl border border-slate-200 p-4 text-center hover:border-[#3ECFAB]/50 transition">
            <div className="text-3xl mb-2">{f.icon}</div>
            <div className="text-sm font-bold text-slate-800">{f.title}</div>
            <div className="text-xs text-slate-500 mt-0.5">{f.desc}</div>
          </div>
        ))}
      </div>
    </div>
  );
}

export default App;
