// ============================================================
// SPORTS LEAGUE MANAGEMENT SYSTEM — Database Store
// In-memory + localStorage persistence with full CRUD + validation
// ============================================================

export interface DB {
  coaches: Coach[];
  teams: Team[];
  players: Player[];
  venues: Venue[];
  seasons: Season[];
  schedules: Schedule[];
  matches: Match[];
  scores: Score[];
  gamelogs: GameLog[];
  standings: Standing[];
  statistics: Statistic[];
  playerprofiles: PlayerProfile[];
  injuries: Injury[];
  awards: Award[];
  equipment: Equipment[];
  sponsors: Sponsor[];
  posters: Poster[];
  tickets: Ticket[];
  fanregistration: FanRegistration[];
  references: Reference[];
}

export interface Coach { id: number; name: string; email: string; phone: string; specialty: string; experience_years: number; }
export interface Team { id: number; name: string; coach_id: number; founded_year: number; home_city: string; logo_url: string; }
export interface Player { id: number; name: string; team_id: number; position: string; age: number; jersey_number: number; }
export interface Venue { id: number; name: string; address: string; capacity: number; surface_type: string; }
export interface Season { id: number; name: string; start_date: string; end_date: string; year: number; status: string; }
export interface Schedule { id: number; match_date: string; home_team_id: number; away_team_id: number; venue_id: number; season_id: number; }
export interface Match { id: number; schedule_id: number; home_team_id: number; away_team_id: number; match_date: string; status: string; attendance: number; }
export interface Score { id: number; match_id: number; team_id: number; quarter: number; points: number; }
export interface GameLog { id: number; match_id: number; player_id: number; event_type: string; minute: number; description: string; }
export interface Standing { id: number; team_id: number; season_id: number; played: number; wins: number; losses: number; draws: number; points: number; }
export interface Statistic { id: number; player_id: number; season_id: number; goals: number; assists: number; yellow_cards: number; red_cards: number; minutes_played: number; }
export interface PlayerProfile { id: number; player_id: number; height_cm: number; weight_kg: number; nationality: string; preferred_foot: string; bio: string; }
export interface Injury { id: number; player_id: number; injury_type: string; severity: string; start_date: string; expected_return: string; }
export interface Award { id: number; name: string; player_id: number; season_id: number; category: string; date_awarded: string; }
export interface Equipment { id: number; team_id: number; item_name: string; quantity: number; condition_status: string; purchase_date: string; }
export interface Sponsor { id: number; name: string; contact_email: string; contract_start: string; contract_end: string; amount: number; }
export interface Poster { id: number; match_id: number; title: string; image_url: string; publish_date: string; description: string; }
export interface Ticket { id: number; match_id: number; ticket_type: string; price: number; quantity_available: number; quantity_sold: number; sale_start: string; sale_end: string; }
export interface FanRegistration { id: number; name: string; email: string; favorite_team_id: number; registered_date: string; phone: string; }
export interface Reference { id: number; table_name: string; record_id: number; note: string; created_at: string; }

export const DB_KEY = 'sports_league_db_v2';

// ---------- SEED DATA ----------
function seed(): DB {
  const coaches: Coach[] = [
    { id: 1, name: 'Carlos Martinez', email: 'c.martinez@league.com', phone: '555-0101', specialty: 'Attacking', experience_years: 12 },
    { id: 2, name: 'David Thompson', email: 'd.thompson@league.com', phone: '555-0102', specialty: 'Defense', experience_years: 8 },
    { id: 3, name: 'Alessandro Rossi', email: 'a.rossi@league.com', phone: '555-0103', specialty: 'Midfield', experience_years: 15 },
    { id: 4, name: 'James O\'Connor', email: 'j.oconnor@league.com', phone: '555-0104', specialty: 'Goalkeeping', experience_years: 10 },
    { id: 5, name: 'Lucas Silva', email: 'l.silva@league.com', phone: '555-0105', specialty: 'Fitness', experience_years: 6 },
  ];

  const venues: Venue[] = [
    { id: 1, name: 'Olympic Stadium', address: '123 Sports Ave, Downtown', capacity: 50000, surface_type: 'Grass' },
    { id: 2, name: 'Riverside Arena', address: '456 River Rd, Westside', capacity: 35000, surface_type: 'Turf' },
    { id: 3, name: 'Central Park Field', address: '789 Park Ln, Midtown', capacity: 25000, surface_type: 'Grass' },
    { id: 4, name: 'Harbor View Grounds', address: '321 Bay St, Harbor', capacity: 40000, surface_type: 'Hybrid' },
    { id: 5, name: 'Summit Dome', address: '555 Peak Blvd, Hills', capacity: 30000, surface_type: 'Turf' },
  ];

  const seasons: Season[] = [
    { id: 1, name: '2024 Spring Season', start_date: '2024-03-01', end_date: '2024-06-30', year: 2024, status: 'Completed' },
    { id: 2, name: '2025 Winter Cup', start_date: '2025-01-10', end_date: '2025-04-20', year: 2025, status: 'Active' },
    { id: 3, name: '2025 Summer League', start_date: '2025-07-01', end_date: '2025-09-30', year: 2025, status: 'Upcoming' },
    { id: 4, name: '2026 Championship', start_date: '2026-02-01', end_date: '2026-05-30', year: 2026, status: 'Upcoming' },
  ];

  const teams: Team[] = [
    { id: 1, name: 'Thunder FC', coach_id: 1, founded_year: 2005, home_city: 'Downtown', logo_url: '' },
    { id: 2, name: 'Storm United', coach_id: 2, founded_year: 2008, home_city: 'Westside', logo_url: '' },
    { id: 3, name: 'Wanderers SC', coach_id: 3, founded_year: 2001, home_city: 'Midtown', logo_url: '' },
    { id: 4, name: 'Titans AFC', coach_id: 4, founded_year: 2010, home_city: 'Harbor', logo_url: '' },
    { id: 5, name: 'Phoenix Rising', coach_id: 5, founded_year: 2015, home_city: 'Hills', logo_url: '' },
    { id: 6, name: 'Crescent Rovers', coach_id: 1, founded_year: 2003, home_city: 'Eastside', logo_url: '' },
  ];

  const players: Player[] = [
    { id: 1, name: 'Marcus Johnson', team_id: 1, position: 'Forward', age: 24, jersey_number: 9 },
    { id: 2, name: 'Ethan Williams', team_id: 1, position: 'Midfielder', age: 27, jersey_number: 8 },
    { id: 3, name: 'Liam O\'Brien', team_id: 2, position: 'Defender', age: 29, jersey_number: 4 },
    { id: 4, name: 'Noah Martinez', team_id: 2, position: 'Forward', age: 22, jersey_number: 11 },
    { id: 5, name: 'Oliver Brown', team_id: 3, position: 'Midfielder', age: 26, jersey_number: 10 },
    { id: 6, name: 'Ava Garcia', team_id: 3, position: 'Defender', age: 25, jersey_number: 3 },
    { id: 7, name: 'Isabella Lee', team_id: 4, position: 'Goalkeeper', age: 28, jersey_number: 1 },
    { id: 8, name: 'Mason Clark', team_id: 4, position: 'Forward', age: 23, jersey_number: 7 },
    { id: 9, name: 'Lucas Walker', team_id: 5, position: 'Midfielder', age: 30, jersey_number: 6 },
    { id: 10, name: 'Mia Hall', team_id: 5, position: 'Defender', age: 21, jersey_number: 5 },
    { id: 11, name: 'Sebastian Young', team_id: 6, position: 'Forward', age: 24, jersey_number: 9 },
    { id: 12, name: 'Jack King', team_id: 6, position: 'Midfielder', age: 26, jersey_number: 8 },
  ];

  const schedules: Schedule[] = [
    { id: 1, match_date: '2025-02-15', home_team_id: 1, away_team_id: 2, venue_id: 1, season_id: 2 },
    { id: 2, match_date: '2025-02-22', home_team_id: 3, away_team_id: 4, venue_id: 2, season_id: 2 },
    { id: 3, match_date: '2025-03-01', home_team_id: 5, away_team_id: 6, venue_id: 3, season_id: 2 },
    { id: 4, match_date: '2025-03-08', home_team_id: 1, away_team_id: 3, venue_id: 1, season_id: 2 },
    { id: 5, match_date: '2025-03-15', home_team_id: 2, away_team_id: 5, venue_id: 4, season_id: 2 },
    { id: 6, match_date: '2025-03-22', home_team_id: 4, away_team_id: 6, venue_id: 5, season_id: 2 },
    { id: 7, match_date: '2025-04-05', home_team_id: 1, away_team_id: 5, venue_id: 1, season_id: 2 },
    { id: 8, match_date: '2025-04-12', home_team_id: 2, away_team_id: 4, venue_id: 2, season_id: 2 },
  ];

  const matches: Match[] = [
    { id: 1, schedule_id: 1, home_team_id: 1, away_team_id: 2, match_date: '2025-02-15', status: 'Completed', attendance: 28500 },
    { id: 2, schedule_id: 2, home_team_id: 3, away_team_id: 4, match_date: '2025-02-22', status: 'Completed', attendance: 22000 },
    { id: 3, schedule_id: 3, home_team_id: 5, away_team_id: 6, match_date: '2025-03-01', status: 'Completed', attendance: 18500 },
    { id: 4, schedule_id: 4, home_team_id: 1, away_team_id: 3, match_date: '2025-03-08', status: 'Completed', attendance: 31000 },
    { id: 5, schedule_id: 5, home_team_id: 2, away_team_id: 5, match_date: '2025-03-15', status: 'Completed', attendance: 20000 },
    { id: 6, schedule_id: 6, home_team_id: 4, away_team_id: 6, match_date: '2025-03-22', status: 'Scheduled', attendance: 0 },
    { id: 7, schedule_id: 7, home_team_id: 1, away_team_id: 5, match_date: '2025-04-05', status: 'Scheduled', attendance: 0 },
    { id: 8, schedule_id: 8, home_team_id: 2, away_team_id: 4, match_date: '2025-04-12', status: 'Scheduled', attendance: 0 },
  ];

  const scores: Score[] = [
    { id: 1, match_id: 1, team_id: 1, quarter: 1, points: 1 },
    { id: 2, match_id: 1, team_id: 1, quarter: 2, points: 1 },
    { id: 3, match_id: 1, team_id: 2, quarter: 1, points: 0 },
    { id: 4, match_id: 1, team_id: 2, quarter: 2, points: 1 },
    { id: 5, match_id: 2, team_id: 3, quarter: 1, points: 2 },
    { id: 6, match_id: 2, team_id: 4, quarter: 1, points: 1 },
    { id: 7, match_id: 2, team_id: 4, quarter: 2, points: 1 },
    { id: 8, match_id: 3, team_id: 5, quarter: 1, points: 0 },
    { id: 9, match_id: 3, team_id: 6, quarter: 1, points: 3 },
    { id: 10, match_id: 4, team_id: 1, quarter: 1, points: 2 },
    { id: 11, match_id: 4, team_id: 3, quarter: 1, points: 1 },
    { id: 12, match_id: 5, team_id: 2, quarter: 1, points: 0 },
    { id: 13, match_id: 5, team_id: 5, quarter: 1, points: 2 },
  ];

  const gamelogs: GameLog[] = [
    { id: 1, match_id: 1, player_id: 1, event_type: 'Goal', minute: 23, description: 'Powerful strike from outside the box' },
    { id: 2, match_id: 1, player_id: 1, event_type: 'Goal', minute: 67, description: 'Header from corner kick' },
    { id: 3, match_id: 1, player_id: 3, event_type: 'Yellow Card', minute: 45, description: 'Reckless challenge' },
    { id: 4, match_id: 2, player_id: 5, event_type: 'Goal', minute: 12, description: 'Counter attack finish' },
    { id: 5, match_id: 2, player_id: 5, event_type: 'Goal', minute: 78, description: 'Free kick goal' },
    { id: 6, match_id: 3, player_id: 11, event_type: 'Goal', minute: 8, description: 'Volley from cross' },
    { id: 7, match_id: 3, player_id: 11, event_type: 'Goal', minute: 34, description: 'Penalty scored' },
    { id: 8, match_id: 3, player_id: 11, event_type: 'Goal', minute: 89, description: 'Last minute winner' },
    { id: 9, match_id: 4, player_id: 2, event_type: 'Assist', minute: 15, description: 'Through ball assist' },
    { id: 10, match_id: 4, player_id: 1, event_type: 'Goal', minute: 16, description: 'One-on-one finish' },
  ];

  const standings: Standing[] = [
    { id: 1, team_id: 1, season_id: 2, played: 3, wins: 3, losses: 0, draws: 0, points: 9 },
    { id: 2, team_id: 6, season_id: 2, played: 1, wins: 1, losses: 0, draws: 0, points: 3 },
    { id: 3, team_id: 3, season_id: 2, played: 2, wins: 1, losses: 1, draws: 0, points: 3 },
    { id: 4, team_id: 5, season_id: 2, played: 2, wins: 1, losses: 1, draws: 0, points: 3 },
    { id: 5, team_id: 2, season_id: 2, played: 2, wins: 0, losses: 2, draws: 0, points: 0 },
    { id: 6, team_id: 4, season_id: 2, played: 1, wins: 0, losses: 1, draws: 0, points: 0 },
    { id: 7, team_id: 1, season_id: 1, played: 10, wins: 7, losses: 2, draws: 1, points: 22 },
    { id: 8, team_id: 2, season_id: 1, played: 10, wins: 5, losses: 3, draws: 2, points: 17 },
  ];

  const statistics: Statistic[] = [
    { id: 1, player_id: 1, season_id: 2, goals: 3, assists: 1, yellow_cards: 1, red_cards: 0, minutes_played: 270 },
    { id: 2, player_id: 11, season_id: 2, goals: 3, assists: 0, yellow_cards: 0, red_cards: 0, minutes_played: 90 },
    { id: 3, player_id: 5, season_id: 2, goals: 2, assists: 1, yellow_cards: 2, red_cards: 0, minutes_played: 180 },
    { id: 4, player_id: 8, season_id: 2, goals: 2, assists: 0, yellow_cards: 0, red_cards: 1, minutes_played: 90 },
    { id: 5, player_id: 4, season_id: 2, goals: 1, assists: 2, yellow_cards: 0, red_cards: 0, minutes_played: 180 },
    { id: 6, player_id: 2, season_id: 2, goals: 0, assists: 3, yellow_cards: 0, red_cards: 0, minutes_played: 270 },
    { id: 7, player_id: 9, season_id: 2, goals: 0, assists: 2, yellow_cards: 3, red_cards: 0, minutes_played: 180 },
    { id: 8, player_id: 3, season_id: 2, goals: 0, assists: 0, yellow_cards: 2, red_cards: 0, minutes_played: 180 },
  ];

  const playerprofiles: PlayerProfile[] = [
    { id: 1, player_id: 1, height_cm: 185, weight_kg: 80, nationality: 'American', preferred_foot: 'Right', bio: 'Clinical striker known for powerful finishing' },
    { id: 2, player_id: 2, height_cm: 178, weight_kg: 74, nationality: 'English', preferred_foot: 'Right', bio: 'Creative playmaker with excellent vision' },
    { id: 3, player_id: 3, height_cm: 183, weight_kg: 78, nationality: 'Irish', preferred_foot: 'Left', bio: 'Tough tackling center back' },
    { id: 4, player_id: 4, height_cm: 175, weight_kg: 70, nationality: 'Spanish', preferred_foot: 'Right', bio: 'Pacy winger with great dribbling' },
    { id: 5, player_id: 5, height_cm: 180, weight_kg: 75, nationality: 'English', preferred_foot: 'Right', bio: 'Box-to-box midfielder' },
    { id: 6, player_id: 6, height_cm: 170, weight_kg: 65, nationality: 'Spanish', preferred_foot: 'Left', bio: 'Attack-minded full back' },
  ];

  const injuries: Injury[] = [
    { id: 1, player_id: 7, injury_type: 'Hamstring Strain', severity: 'Moderate', start_date: '2025-02-20', expected_return: '2025-04-01' },
    { id: 2, player_id: 10, injury_type: 'Ankle Sprain', severity: 'Mild', start_date: '2025-03-05', expected_return: '2025-03-25' },
    { id: 3, player_id: 12, injury_type: 'Knee Ligament', severity: 'Severe', start_date: '2025-01-15', expected_return: '2025-06-01' },
  ];

  const awards: Award[] = [
    { id: 1, name: 'Player of the Month', player_id: 1, season_id: 2, category: 'Best Player', date_awarded: '2025-03-01' },
    { id: 2, name: 'Top Scorer', player_id: 11, season_id: 2, category: 'Goals', date_awarded: '2025-03-15' },
    { id: 3, name: 'Best Defender', player_id: 3, season_id: 1, category: 'Defense', date_awarded: '2024-06-30' },
    { id: 4, name: 'MVP', player_id: 5, season_id: 1, category: 'Overall', date_awarded: '2024-06-30' },
  ];

  const equipment: Equipment[] = [
    { id: 1, team_id: 1, item_name: 'Match Balls', quantity: 20, condition_status: 'New', purchase_date: '2025-01-10' },
    { id: 2, team_id: 1, item_name: 'Training Cones', quantity: 50, condition_status: 'Good', purchase_date: '2024-08-15' },
    { id: 3, team_id: 2, item_name: 'Goalkeeper Gloves', quantity: 10, condition_status: 'New', purchase_date: '2025-02-01' },
    { id: 4, team_id: 3, item_name: 'Training Bibs', quantity: 30, condition_status: 'Good', purchase_date: '2024-06-20' },
    { id: 5, team_id: 4, item_name: 'Resistance Bands', quantity: 15, condition_status: 'Fair', purchase_date: '2024-03-10' },
    { id: 6, team_id: 5, item_name: 'Medical Kit', quantity: 5, condition_status: 'New', purchase_date: '2025-01-20' },
  ];

  const sponsors: Sponsor[] = [
    { id: 1, name: 'Nova Sportswear', contact_email: 'deals@novasports.com', contract_start: '2025-01-01', contract_end: '2025-12-31', amount: 500000 },
    { id: 2, name: 'Apex Nutrition', contact_email: 'partners@apexnutri.com', contract_start: '2025-02-01', contract_end: '2026-01-31', amount: 250000 },
    { id: 3, name: 'Peak Financial', contact_email: 'sponsorship@peakfin.com', contract_start: '2024-07-01', contract_end: '2025-06-30', amount: 750000 },
    { id: 4, name: 'Vertex Energy', contact_email: 'info@vertexenergy.com', contract_start: '2025-03-01', contract_end: '2026-02-28', amount: 300000 },
  ];

  const posters: Poster[] = [
    { id: 1, match_id: 1, title: 'Thunder vs Storm — Opening Clash!', image_url: '', publish_date: '2025-02-01', description: 'The season kicks off with a fierce rivalry match' },
    { id: 2, match_id: 2, title: 'Wanderers Host Titans', image_url: '', publish_date: '2025-02-10', description: 'Midfield battle awaits at Riverside' },
    { id: 3, match_id: 7, title: 'Thunder vs Phoenix — Semifinal', image_url: '', publish_date: '2025-03-20', description: 'Do not miss this epic semifinal showdown' },
  ];

  const tickets: Ticket[] = [
    { id: 1, match_id: 6, ticket_type: 'General Admission', price: 25, quantity_available: 10000, quantity_sold: 4500, sale_start: '2025-02-20', sale_end: '2025-03-22' },
    { id: 2, match_id: 6, ticket_type: 'VIP', price: 120, quantity_available: 500, quantity_sold: 500, sale_start: '2025-02-20', sale_end: '2025-03-22' },
    { id: 3, match_id: 7, ticket_type: 'General Admission', price: 30, quantity_available: 15000, quantity_sold: 8200, sale_start: '2025-03-01', sale_end: '2025-04-05' },
    { id: 4, match_id: 7, ticket_type: 'Premium', price: 75, quantity_available: 2000, quantity_sold: 1300, sale_start: '2025-03-01', sale_end: '2025-04-05' },
    { id: 5, match_id: 8, ticket_type: 'General Admission', price: 28, quantity_available: 12000, quantity_sold: 3100, sale_start: '2025-03-10', sale_end: '2025-04-12' },
  ];

  const fanregistration: FanRegistration[] = [
    { id: 1, name: 'John Doe', email: 'john.doe@gmail.com', favorite_team_id: 1, registered_date: '2025-01-15', phone: '555-1001' },
    { id: 2, name: 'Jane Smith', email: 'jane.smith@yahoo.com', favorite_team_id: 3, registered_date: '2025-01-20', phone: '555-1002' },
    { id: 3, name: 'Robert Chen', email: 'r.chen@outlook.com', favorite_team_id: 2, registered_date: '2025-02-01', phone: '555-1003' },
    { id: 4, name: 'Sarah Patel', email: 's.patel@gmail.com', favorite_team_id: 4, registered_date: '2025-02-10', phone: '555-1004' },
    { id: 5, name: 'Michael Brown', email: 'm.brown@hotmail.com', favorite_team_id: 5, registered_date: '2025-02-15', phone: '555-1005' },
    { id: 6, name: 'Emily Davis', email: 'e.davis@gmail.com', favorite_team_id: 1, registered_date: '2025-02-28', phone: '555-1006' },
    { id: 7, name: 'Daniel Kim', email: 'd.kim@gmail.com', favorite_team_id: 6, registered_date: '2025-03-05', phone: '555-1007' },
  ];

  const references: Reference[] = [
    { id: 1, table_name: 'teams', record_id: 1, note: 'Top performing team — key franchise', created_at: '2025-01-01' },
    { id: 2, table_name: 'seasons', record_id: 2, note: 'Current active season', created_at: '2025-01-10' },
    { id: 3, table_name: 'venues', record_id: 1, note: 'Primary venue for finals', created_at: '2025-01-05' },
  ];

  return { coaches, teams, players, venues, seasons, schedules, matches, scores, gamelogs, standings, statistics, playerprofiles, injuries, awards, equipment, sponsors, posters, tickets, fanregistration, references };
}

// ---------- VALIDATION ----------
export interface FieldDef {
  name: string; label: string; type: 'text' | 'number' | 'date' | 'select' | 'textarea';
  required?: boolean; min?: number; max?: number; minLength?: number;
  lettersOnly?: boolean; email?: boolean;
  options?: { value: string | number; label: string }[];
  duplicateCheck?: boolean; // duplicate name check in same table
}

export interface ValidationError { [field: string]: string; }

const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const LETTERS_RE = /^[a-zA-Z\s'.]+$/;

export function validateRecord(
  table: keyof DB,
  record: Record<string, any>,
  fields: FieldDef[],
  db: DB
): ValidationError {
  const errors: ValidationError = {};

  for (const f of fields) {
    const val = record[f.name];
    const strVal = (val ?? '').toString().trim();

    // Required
    if (f.required && (val === undefined || val === null || strVal === '')) {
      errors[f.name] = `${f.label} is required`;
      continue;
    }

    // If empty and not required, skip further checks
    if (strVal === '' || val === undefined || val === null) continue;

    // lettersOnly
    if (f.lettersOnly && !LETTERS_RE.test(strVal)) {
      errors[f.name] = `${f.label} must contain only letters, spaces, apostrophes, or dots`;
    }

    // minLength
    if (f.minLength && strVal.length < f.minLength) {
      errors[f.name] = `${f.label} must be at least ${f.minLength} characters`;
    }

    // email
    if (f.email && !EMAIL_RE.test(strVal)) {
      errors[f.name] = `${f.label} must be a valid email address`;
    }

    // number min/max
    if (f.type === 'number') {
      const n = Number(val);
      if (isNaN(n)) {
        errors[f.name] = `${f.label} must be a number`;
      } else {
        if (f.min !== undefined && n < f.min) errors[f.name] = `${f.label} must be ≥ ${f.min}`;
        if (f.max !== undefined && n > f.max) errors[f.name] = `${f.label} must be ≤ ${f.max}`;
      }
    }
  }

  // Duplicate name check
  for (const f of fields) {
    if (f.duplicateCheck) {
      const val = (record[f.name] ?? '').toString().trim().toLowerCase();
      if (val) {
        const tableRows = db[table] as any[];
        const dup = tableRows.find(r =>
          r.id !== record.id &&
          (r[f.name] ?? '').toString().trim().toLowerCase() === val
        );
        if (dup) {
          errors[f.name] = `${f.label} "${record[f.name]}" already exists`;
        }
      }
    }
  }

  return errors;
}

// Extra cross-field validations
export function extraValidation(
  table: keyof DB,
  record: Record<string, any>,
  db: DB
): ValidationError {
  const errors: ValidationError = {};

  // Home ≠ Away team (schedules & matches)
  if (table === 'schedules' || table === 'matches') {
    if (record.home_team_id && record.away_team_id &&
      Number(record.home_team_id) === Number(record.away_team_id)) {
      errors.away_team_id = 'Away team cannot be the same as home team';
    }
  }

  // Date ranges
  if (table === 'seasons') {
    if (record.start_date && record.end_date && record.start_date >= record.end_date) {
      errors.end_date = 'End date must be after start date';
    }
  }
  if (table === 'sponsors') {
    if (record.contract_start && record.contract_end && record.contract_start >= record.contract_end) {
      errors.contract_end = 'Contract end must be after contract start';
    }
  }
  if (table === 'tickets') {
    if (record.sale_start && record.sale_end && record.sale_start >= record.sale_end) {
      errors.sale_end = 'Sale end must be after sale start';
    }
  }

  // Standings: W+L+D ≤ played
  if (table === 'standings') {
    const p = Number(record.played) || 0;
    const w = Number(record.wins) || 0;
    const l = Number(record.losses) || 0;
    const d = Number(record.draws) || 0;
    if (w + l + d > p) {
      errors.played = `Wins+Losses+Draws (${w + l + d}) must not exceed Played (${p})`;
    }
  }

  // Tickets: quantity_sold ≤ quantity_available
  if (table === 'tickets') {
    const avail = Number(record.quantity_available) || 0;
    const sold = Number(record.quantity_sold) || 0;
    if (sold > avail) {
      errors.quantity_sold = `Sold (${sold}) cannot exceed available (${avail})`;
    }
  }

  // Duplicate fan email
  if (table === 'fanregistration') {
    const email = (record.email ?? '').toString().trim().toLowerCase();
    if (email) {
      const dup = db.fanregistration.find(r =>
        r.id !== record.id &&
        r.email.trim().toLowerCase() === email
      );
      if (dup) errors.email = 'A fan with this email is already registered';
    }
  }

  return errors;
}

// ---------- LOCAL STORAGE ----------
function loadDB(): DB {
  try {
    const raw = localStorage.getItem(DB_KEY);
    if (raw) return JSON.parse(raw);
  } catch {}
  const fresh = seed();
  saveDB(fresh);
  return fresh;
}

function saveDB(db: DB) {
  localStorage.setItem(DB_KEY, JSON.stringify(db));
}

// ---------- HOOK ----------
import { useState, useCallback, useEffect } from 'react';

export interface ToastMsg { id: number; type: 'success' | 'error' | 'info'; text: string; }

export function useDB() {
  const [db, setDb] = useState<DB>(() => loadDB());
  const [toasts, setToasts] = useState<ToastMsg[]>([]);

  useEffect(() => { saveDB(db); }, [db]);

  const showToast = useCallback((type: ToastMsg['type'], text: string) => {
    const id = Date.now() + Math.random();
    setToasts(t => [...t, { id, type, text }]);
    setTimeout(() => {
      setToasts(t => t.filter(x => x.id !== id));
    }, 3000);
  }, []);

  const totalRecords = useCallback(() => {
    return Object.values(db).reduce((s, arr) => s + (arr as any[]).length, 0);
  }, [db]);

  const moduleCount = 20;

  const nextId = (arr: any[]) => arr.length === 0 ? 1 : Math.max(...arr.map(r => r.id)) + 1;

  const create = useCallback(<K extends keyof DB>(table: K, record: Omit<DB[K][number], 'id'>) => {
    setDb(prev => {
      const arr = [...(prev[table] as any[])];
      const newRec = { ...record, id: nextId(arr) } as any;
      arr.push(newRec);
      return { ...prev, [table]: arr };
    });
    showToast('success', 'Record created successfully');
  }, [showToast]);

  const update = useCallback(<K extends keyof DB>(table: K, id: number, record: Partial<DB[K][number]>) => {
    setDb(prev => {
      const arr = (prev[table] as any[]).map(r => r.id === id ? { ...r, ...record } : r);
      return { ...prev, [table]: arr };
    });
    showToast('success', 'Record updated successfully');
  }, [showToast]);

  const remove = useCallback(<K extends keyof DB>(table: K, id: number) => {
    setDb(prev => {
      const arr = (prev[table] as any[]).filter(r => r.id !== id);
      return { ...prev, [table]: arr };
    });
    showToast('success', 'Record deleted');
  }, [showToast]);

  const reset = useCallback(() => {
    const fresh = seed();
    setDb(fresh);
    saveDB(fresh);
    showToast('info', 'Database reset to seed data');
  }, [showToast]);

  return { db, setDb, create, update, remove, reset, toasts, showToast, totalRecords, moduleCount };
}
