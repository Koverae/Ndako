/* --------------------------------- Helpers -------------------------------- */

export function pad(n: number) { return String(n).padStart(2, "0"); }

export const fmtTime = (iso?: string) => {
  if (!iso) return "";
  try { return new Date(iso).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" }); } catch { return ""; }
};

export const dayKey = (d: Date) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;

export const humanListTime = (iso: string) => {
  const d = new Date(iso);
  const now = new Date();

  const today = dayKey(now);
  const y = new Date(now);
  y.setDate(now.getDate() - 1);
  const yKey = dayKey(y);

  const dKey = dayKey(d);

  if (dKey === today) return d.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
  if (dKey === yKey) return "Yesterday";
  return `${pad(d.getDate())}/${pad(d.getMonth() + 1)}/${d.getFullYear()}`;
};

export const labelForDate = (iso: string) => {
  const d = new Date(iso);
  const now = new Date();
  const todayKey = dayKey(now);
  const yest = new Date(now);
  yest.setDate(now.getDate() - 1);
  const yKey = dayKey(yest);
  const mKey = dayKey(d);
  if (mKey === todayKey) return "Today";
  if (mKey === yKey) return "Yesterday";
  return `${pad(d.getDate())}/${pad(d.getMonth() + 1)}/${d.getFullYear()}`;
};

export const cx = (...c: Array<string | false | null | undefined>) => c.filter(Boolean).join(" ");
