// resources/js/components/chat/ChatWidget.tsx
// -----------------------------------------------------------------------------
// Ndako Chat – React (componentized, smooth updates, WhatsApp-inspired)
// -----------------------------------------------------------------------------
// What’s new in this version:
// • Componentized: <Fab>, <Panel>, <Sidebar>, <ConvoRow>, <Thread>, <Composer>
// • No hard refresh after sending a message — only local state updates.
// • Human-friendly timestamps in the conversation list (Today HH:mm, Yesterday,
//   or dd/mm/yyyy).
// • “Ndako Messenger” empty thread is EXPANDED on desktop (fills thread) and
//   HIDDEN on small screens.
// • Overflow-safe everywhere (100dvh, scrollable lists), responsive desktop/mobile.
// • Keeps WhatsApp-like visuals (patterned bg, refined bubbles, tails).
// -----------------------------------------------------------------------------

import React, { useEffect, useMemo, useRef, useState } from "react";
import axios from "axios";
import Sidebar from "./Sidebar";
import { cx, dayKey, fmtTime, humanListTime, labelForDate } from "../../data/helpers";
import { Conversation, ID, Message, Paginated } from "../../data/type";
import Panel from "./Panel";
import Composer from "./Composer";
import Thread from "./Thread";
import { ChatAPI } from "../../lib/api";
import Fab from "./Fab";




/* --------------------------------- Helpers -------------------------------- */

function useWindowSize() {
  const [w, setW] = useState<number>(typeof window !== "undefined" ? window.innerWidth : 1024);
  useEffect(() => {
    const onR = () => setW(window.innerWidth);
    window.addEventListener("resize", onR);
    return () => window.removeEventListener("resize", onR);
  }, []);
  return { width: w, isMobile: w <= 640 };
}


/* ------------------------------ Main Component ---------------------------- */
export default function ChatWidget() {
  const { isMobile } = useWindowSize();

  /* Panel & layout */
  const [open, setOpen] = useState(false);

  /* Sidebar state */
  const [convos, setConvos] = useState<Conversation[]>([]);
  const [selected, setSelected] = useState<ID | null>(null);
  const [unreadTotal, setUnreadTotal] = useState(0);
  const [loadingConvos, setLoadingConvos] = useState(false);
  const [errorConvos, setErrorConvos] = useState<string | null>(null);

  /* Messages paging cache: per-conversation */
  const [msgPages, setMsgPages] = useState<
    Record<ID, { data: Message[]; nextPage: number | null; loading?: boolean; error?: string | null }>
  >({});
  const bodyRef = useRef<HTMLDivElement | null>(null);

  /* Compose */
  const [messageText, setMessageText] = useState("");
  const [uploads, setUploads] = useState<File[]>([]);
  const [uploading, setUploading] = useState(false);
  const canSend = messageText.trim().length > 0;

  /* Contacts search */
  const [search, setSearch] = useState("");
  const [showContacts, setShowContacts] = useState(false);
  const [contacts, setContacts] = useState<
    Array<{ type: "user" | "guest"; id: ID; name: string; label: string; avatar?: string | null }>
  >([]);
  const [searchLoading, setSearchLoading] = useState(false);

  /* Emoji / attach / mic UI */
  const [attachOpen, setAttachOpen] = useState(false);
  const [emojiOpen, setEmojiOpen] = useState(false);
  const [emojiTab, setEmojiTab] = useState<"smileys" | "gestures" | "symbols">("smileys");
  const [recording, setRecording] = useState(false);
  const [recSec, setRecSec] = useState(0);
  const recTimerRef = useRef<number | null>(null);

  /* Context menus */
  const [convMenu, setConvMenu] = useState<{ open: boolean; x: number; y: number }>({ open: false, x: 0, y: 0 });
  const [msgMenu, setMsgMenu] = useState<{ open: boolean; x: number; y: number; id: ID | null; mine: boolean }>({
    open: false,
    x: 0,
    y: 0,
    id: null,
    mine: false,
  });
  const hideAll = () => {
    setConvMenu({ open: false, x: 0, y: 0 });
    setMsgMenu({ open: false, x: 0, y: 0, id: null, mine: false });
  };
  const openConv = (e: React.MouseEvent) => { hideAll(); setConvMenu({ open: true, x: e.clientX, y: e.clientY }); };
  const openMsg = (x: number, y: number, id: ID, mine: boolean) => { hideAll(); setMsgMenu({ open: true, x, y, id, mine }); };
  const pressTimerRef = useRef<number | null>(null);
  const pressStart = (e: React.TouchEvent | React.MouseEvent, id: ID, mine: boolean) => {
    pressClear();
    const p = "touches" in e ? e.touches[0] : (e as React.MouseEvent);
    pressTimerRef.current = window.setTimeout(() => openMsg(p.clientX, p.clientY, id, mine), 450);
  };
  const pressClear = () => { if (pressTimerRef.current) { clearTimeout(pressTimerRef.current); pressTimerRef.current = null; } };

/* ------------------------------- Context Menu ----------------------------- */
function ContextMenu({
  open,
  x,
  y,
  onClose,
  children,
  className,
}: {
  open: boolean;
  x: number;
  y: number;
  onClose: () => void;
  children: React.ReactNode;
  className?: string;
}) {
  const ref = useRef<HTMLDivElement | null>(null);
  const [pos, setPos] = useState<{ left: number; top: number }>({ left: x, top: y });

  // Smart clamp to viewport after mount/size known
  useEffect(() => {
    if (!open) return;
    const el = ref.current;
    if (!el) return;
    const vw = window.innerWidth;
    const vh = window.innerHeight;
    const rect = el.getBoundingClientRect();
    const pad = 8;
    const left = Math.min(Math.max(x, pad), vw - rect.width - pad);
    const top = Math.min(Math.max(y, pad), vh - rect.height - pad);
    setPos({ left, top });
  }, [open, x, y]);

  if (!open) return null;
  return (
    <div
      ref={ref}
      className={cx("ndako-menu", className)}
      style={{
        position: "fixed",
        left: pos.left,
        top: pos.top,
        zIndex: 2147483600,
        minWidth: 220,
        background: "#fff",
        border: "1px solid rgba(0,0,0,.08)",
        borderRadius: 12,
        boxShadow: "0 12px 28px rgba(0,0,0,.18)",
        overflow: "hidden",
      }}
      onClick={(e) => e.stopPropagation()}
      onContextMenu={(e) => e.preventDefault()}
    >
      {children}
    </div>
  );
}

function MenuItem({
  icon,
  children,
  danger,
  onClick,
}: {
  icon?: React.ReactNode;
  children: React.ReactNode;
  danger?: boolean;
  onClick?: () => void;
}) {
  return (
    <button
      type="button"
      onClick={onClick}
      style={{
        width: "100%",
        display: "flex",
        alignItems: "center",
        gap: 10,
        padding: "10px 12px",
        background: "transparent",
        border: 0,
        textAlign: "left",
        cursor: "pointer",
        color: danger ? "#d32f2f" : "inherit",
      }}
      className="ndako-menu__item"
    >
      <span style={{ width: 18, display: "inline-flex", justifyContent: "center" }}>{icon}</span>
      <span style={{ flex: 1 }}>{children}</span>
    </button>
  );
}

  /* --------------------------- Load conversations ------------------------- */
  async function refreshConversations(page = 1) {
    setLoadingConvos(true);
    setErrorConvos(null);
    try {
      const res = await ChatAPI.conversations(page);
      const data = (res.data ?? []).map((c) => ({ ...c, pinned: !!c.pinned, muted: !!c.muted }));
      setConvos((prev) => {
        // keep client-only flags if they existed
        const map = new Map(prev.map((p) => [p.id, p]));
        return data.map((d) => ({ ...d, pinned: map.get(d.id)?.pinned ?? d.pinned, muted: map.get(d.id)?.muted ?? d.muted }));
      });
      setUnreadTotal(data.reduce((sum, c) => sum + (c.unread ?? 0), 0));
    } catch (e: any) {
      setErrorConvos(e?.message ?? "Failed to load conversations.");
    } finally {
      setLoadingConvos(false);
    }
  }
  useEffect(() => { refreshConversations().catch(() => {}); }, []);

/* ------------------------------ Menus Controller -------------------------- */
function useContextMenus() {
  const [convMenu, setConvMenu] = useState<{ open: boolean; x: number; y: number }>({ open: false, x: 0, y: 0 });
  const [msgMenu, setMsgMenu] = useState<{ open: boolean; x: number; y: number; id: ID | null; mine: boolean }>({
    open: false,
    x: 0,
    y: 0,
    id: null,
    mine: false,
  });
  const pressTimerRef = useRef<number | null>(null);

  const hideAll = () => {
    setConvMenu({ open: false, x: 0, y: 0 });
    setMsgMenu({ open: false, x: 0, y: 0, id: null, mine: false });
  };

  const openConv = (e: React.MouseEvent) => {
    hideAll();
    setConvMenu({ open: true, x: e.clientX, y: e.clientY });
  };

  const openMsg = (clientX: number, clientY: number, id: ID, mine: boolean) => {
    hideAll();
    setMsgMenu({ open: true, x: clientX, y: clientY, id, mine });
  };

  const pressStart = (e: React.TouchEvent | React.MouseEvent, id: ID, mine: boolean) => {
    pressClear();
    const p = "touches" in e ? e.touches[0] : (e as React.MouseEvent);
    pressTimerRef.current = window.setTimeout(() => openMsg(p.clientX, p.clientY, id, mine), 450);
  };
  const pressClear = () => {
    if (pressTimerRef.current) {
      clearTimeout(pressTimerRef.current);
      pressTimerRef.current = null;
    }
  };

  return { convMenu, msgMenu, hideAll, openConv, openMsg, pressStart, pressClear };
}

  /* --------------------------- Select conversation ------------------------ */
  async function selectConversation(id: ID) {
    setSelected(id);
    setAttachOpen(false);
    setEmojiOpen(false);

    if (!msgPages[id]) {
      setMsgPages((s) => ({ ...s, [id]: { data: [], nextPage: null, loading: true, error: null } }));
      try {
        const res = await ChatAPI.messages(id, 1, 40);
        const data = res?.data ?? [];
        const next = (res.meta?.current_page ?? 1) < (res.meta?.last_page ?? 1) ? (res.meta!.current_page + 1) : null;

        setMsgPages((s) => ({ ...s, [id]: { data, nextPage: next, loading: false, error: null } }));

        // Locally mark as read (no full refresh)
        setConvos((list) =>
          list
            .map((c) => (c.id === id ? { ...c, unread: 0 } : c))
            .sort((a, b) => (a.pinned === b.pinned ? (new Date(b.updated_at).getTime() - new Date(a.updated_at).getTime()) : Number(b.pinned) - Number(a.pinned)))
        );
        setUnreadTotal((u) => {
          const conv = convos.find((x) => x.id === id);
          return conv ? Math.max(0, u - (conv.unread || 0)) : u;
        });

        setTimeout(scrollBottom, 0);
        ChatAPI.markRead(id).catch(() => {});
      } catch (e: any) {
        setMsgPages((s) => ({ ...s, [id]: { data: [], nextPage: null, loading: false, error: e?.message ?? "Failed to load messages." } }));
      }
    } else {
      setTimeout(scrollBottom, 0);
    }
  }

  /* ------------------------------- Load older ----------------------------- */
  async function loadOlder() {
    if (!selected) return;
    const state = msgPages[selected];
    if (!state || state.loading || !state.nextPage) return;

    setMsgPages((s) => ({ ...s, [selected]: { ...s[selected], loading: true, error: null } }));
    try {
      const res = await ChatAPI.messages(selected, state.nextPage, 40);
      const chunk = res?.data ?? [];
      const next = (res.meta?.current_page ?? 1) < (res.meta?.last_page ?? 1) ? (res.meta!.current_page + 1) : null;

      setMsgPages((s) => ({
        ...s,
        [selected!]: { data: [...s[selected!].data, ...chunk], nextPage: next, loading: false, error: null },
      }));
    } catch (e: any) {
      setMsgPages((s) => ({
        ...s,
        [selected!]: { ...s[selected!], loading: false, error: e?.message ?? "Failed to load older messages." },
      }));
    }
  }

  /* --------------------------------- Actions ------------------------------ */
  const locallySortConvos = (list: Conversation[]) =>
    list.sort((a, b) => (a.pinned === b.pinned ? (new Date(b.updated_at).getTime() - new Date(a.updated_at).getTime()) : Number(b.pinned) - Number(a.pinned)));

  function togglePin(id: ID) {
    setConvos((list) => locallySortConvos(list.map((c) => (c.id === id ? { ...c, pinned: !c.pinned } : c))));
  }
  function toggleMute(id: ID) {
    setConvos((list) => list.map((c) => (c.id === id ? { ...c, muted: !c.muted } : c)));
  }
  async function markAsRead(id: ID) {
    setConvos((list) => {
      const updated = list.map((c) => (c.id === id ? { ...c, unread: 0 } : c));
      setUnreadTotal(updated.reduce((sum, c) => sum + (c.unread || 0), 0));
      return updated;
    });
    ChatAPI.markRead(id).catch(() => {});
  }
  async function markAsUnread(id: ID) {
    setConvos((list) => {
      const updated = list.map((c) => (c.id === id ? { ...c, unread: Math.max(1, c.unread || 0) } : c));
      setUnreadTotal(updated.reduce((sum, c) => sum + (c.unread || 0), 0));
      return updated;
    });
    ChatAPI.markUnread(id).catch(() => {});
  }
  async function closeConversation(id: ID) {
    setConvos((list) => list.map((c) => (c.id === id ? { ...c, status: "closed" } : c)));
    if (selected === id) setSelected(null);
    ChatAPI.close(id).catch(() => {});
  }
  async function deleteConversation(id: ID) {
    setConvos((list) => list.filter((c) => c.id !== id));
    if (selected === id) setSelected(null);
    setUnreadTotal((u) => {
      const conv = convos.find((x) => x.id === id);
      return conv ? Math.max(0, u - (conv.unread || 0)) : u;
    });
    ChatAPI.destroy(id).catch(() => {});
  }

  /* --------------------------------- Send --------------------------------- */
  function updateConvoAfterSend(message: Message) {
    // Move convo to top, update last + time and keep pin sorting
    setConvos((list) => {
      const idx = list.findIndex((c) => c.id === message.conversation_id);
      if (idx === -1) return list;
      const cv = list[idx];
      const updated: Conversation = {
        ...cv,
        last: {
          id: message.id,
          body: message.body,
          sender_type: message.sender_type,
          sender_id: message.sender_id,
          created_at: message.created_at,
        },
        updated_at: message.created_at,
      };
      const newList = [updated, ...list.filter((x) => x.id !== cv.id)];
      return locallySortConvos(newList);
    });
  }

  async function handleSend(e: React.FormEvent) {
    e.preventDefault();
    if (!selected || (!canSend && uploads.length === 0)) return;

    try {
      setUploading(uploads.length > 0);
      const msg = await ChatAPI.send(selected, messageText.trim(), uploads);

      // Update message list (newest-first cache)
      setMsgPages((s) => ({
        ...s,
        [selected]: {
          ...(s[selected] || { data: [], nextPage: null }),
          data: [msg, ...(s[selected]?.data || [])],
          nextPage: s[selected]?.nextPage ?? null,
          loading: false,
          error: null,
        },
      }));

      // Update convo list locally only (no full refresh)
      updateConvoAfterSend(msg);

      setMessageText("");
      setUploads([]);
      setTimeout(scrollBottom, 0);
    } finally {
      setUploading(false);
    }
  }

  /* ------------------------------- Search UX ------------------------------ */
  useEffect(() => {
    const t = setTimeout(async () => {
      const term = search.trim();
      setShowContacts(term.length > 0);
      if (!term) {
        setContacts([]);
        return;
      }
      setSearchLoading(true);
      try {
        const res = await ChatAPI.searchContacts(term);
        setContacts(res);
      } finally {
        setSearchLoading(false);
      }
    }, 250);
    return () => clearTimeout(t);
  }, [search]);

  async function startWith(contact: { type: "user" | "guest"; id: ID }) {
    const conv = await ChatAPI.startConversation(contact.type, contact.id);
    setShowContacts(false);
    setSearch("");
    // prepend convo locally if not present
    setConvos((list) => {
      if (list.find((c) => c.id === conv.id)) return list;
      return locallySortConvos([{ ...conv, pinned: false, muted: false }, ...list]);
    });
    selectConversation(conv.id);
    setOpen(true);
  }

  /* ------------------------------- Derived UI ----------------------------- */
  const current = useMemo(() => convos.find((c) => c.id === selected) || null, [convos, selected]);

  const currentMessages: Message[] = useMemo(() => {
    if (!selected) return [];
    const page = msgPages[selected];
    if (!page) return [];
    return [...page.data].reverse();
  }, [selected, msgPages]);

  const todayGroups = useMemo(() => {
    const groups: Record<string, Message[]> = {};
    currentMessages.forEach((m) => {
      const d = new Date(m.created_at);
      const key = dayKey(d);
      (groups[key] = groups[key] || []).push(m);
    });
    return Object.keys(groups).map((k) => ({
      key: k,
      label: labelForDate(groups[k][0].created_at),
      messages: groups[k],
    }));
  }, [currentMessages]);

  function scrollBottom() {
    const el = bodyRef.current;
    if (el) el.scrollTop = el.scrollHeight;
  }

  // Mic UI only
  const startRec = () => {
    if (!selected || recording) return;
    setRecording(true);
    setRecSec(0);
    recTimerRef.current = window.setInterval(() => setRecSec((s) => s + 1), 1000);
  };
  const stopRec = () => {
    if (!recording) return;
    setRecording(false);
    if (recTimerRef.current) clearInterval(recTimerRef.current);
    recTimerRef.current = null;
  };
  const recTime = `${String(Math.floor(recSec / 60)).padStart(2, "0")}:${String(recSec % 60).padStart(2, "0")}`;

  /* ------------------------------- Layout calc ---------------------------- */
  const DESKTOP_EXPANDED_W = 960;   // when a conversation is open
  const DESKTOP_IDLE_W = 540;       // wider so "Ndako Messenger" area looks expanded
  const MOBILE_MAX_W = 480;

  const PANEL_WIDTH =
    !open
      ? 0
      : isMobile
        ? Math.min(window.innerWidth - 16, MOBILE_MAX_W)
        : selected
          ? Math.min(DESKTOP_EXPANDED_W, Math.floor(window.innerWidth * 0.64))
          : Math.min(DESKTOP_IDLE_W, Math.floor(window.innerWidth * 0.46));

  /* --------------------------------- Styles -------------------------------- */
  const globalStyles = `
    .ndako-chat * { box-sizing: border-box; }
    .ndako-chat .ndako-elevate { box-shadow: 0 12px 30px rgba(0,0,0,.22); }
    .ndako-chat .ndako-chat__panel {
      pointer-events: auto;
      background: #ffffff;
      border-radius: 16px;
      overflow: hidden;
      border: 1px solid rgba(0,0,0,.06);
      max-height: calc(100dvh - 24px - env(safe-area-inset-bottom, 0px));
      /* fixed stack */
      position: fixed;
      right: 16px;
      bottom: calc(16px + env(safe-area-inset-bottom, 0px) + 70px);
      display: flex;
    }
    @supports not (height: 100dvh) {
      .ndako-chat .ndako-chat__panel { max-height: calc(100vh - 24px); }
    }

    .ndako-chat .ndako-chat__sidebar {
      height: 100%;
      border-right: 1px solid rgba(0,0,0,.06);
      background: #ffffff;
      display: flex; flex-direction: column;
      min-width: 0; overflow: hidden;
    }
    .ndako-chat .ndako-chat__thread {
      display: flex; flex-direction: column;
      min-width: 0; height: 100%; overflow: hidden;
      background: linear-gradient(180deg,#efeae2 0,#e9e3d9 100%);
    }
    .ndako-chat .ndako-chat__header {
      display: flex; align-items: center; justify-content: space-between;
      padding: 10px 12px; border-bottom: 1px solid rgba(0,0,0,.06);
      background: linear-gradient(180deg,#f7f9fb 0,#eef1f5 100%);
    }
    .ndako-chat .ndako-chat__convos { overflow-y: auto; }
    .ndako-chat .ndako-chat__body {
      flex: 1; min-height: 0; overflow-y: auto; padding: 12px 12px 8px;
      background:
        radial-gradient(circle at 1px 1px, rgba(0,0,0,.03) 1px, transparent 0) 0 0/10px 10px,
        linear-gradient(0deg, rgba(0,0,0,0) 0, rgba(0,0,0,.02) 100%);
    }
    .ndako-chat .ndako-chat__inputbar {
      display: flex; align-items: center; gap: 8px;
      padding: 8px; background: #f3f5f7; border-top: 1px solid rgba(0,0,0,.06);
    }
    .ndako-chat .ndako-chat__input {
      flex: 1; min-width: 0; border: none; outline: none; background: #fff;
      border-radius: 22px; padding: 10px 12px; font-size: 14px;
      box-shadow: inset 0 0 0 1px rgba(0,0,0,.06);
    }

    .ndako-chat .ndako-chat__msg { display: flex; margin: 6px 0; }
    .ndako-chat .ndako-chat__msg.is-user { justify-content: flex-end; }
    .ndako-chat .ndako-chat__msg.is-agent { justify-content: flex-start; }
    .ndako-chat .ndako-chat__bubble {
      position: relative; max-width: 78%; padding: 8px 10px 18px;
      font-size: 14px; line-height: 1.35; border-radius: 12px;
      box-shadow: 0 1px 0 rgba(0,0,0,.06); word-wrap: break-word; white-space: pre-wrap;
    }
    .ndako-chat .ndako-chat__bubble.b-user {
      background: linear-gradient(180deg,#dcffd7 0,#c9f5c2 100%);
      border-top-right-radius: 4px;
    }
    .ndako-chat .ndako-chat__bubble.b-peer {
      background: #fff; border-top-left-radius: 4px;
    }
    .ndako-chat .ndako-chat__bubble.b-user::after,
    .ndako-chat .ndako-chat__bubble.b-peer::after {
      content: ""; position: absolute; bottom: 0; width: 0; height: 0; border: 8px solid transparent;
    }
    .ndako-chat .ndako-chat__bubble.b-user::after {
      right: -1px; border-left-color: #c9f5c2; border-right: 0; border-bottom: 0;
    }
    .ndako-chat .ndako-chat__bubble.b-peer::after {
      left: -1px; border-right-color: #fff; border-left: 0; border-bottom: 0;
    }
    .ndako-chat .ndako-chat__time {
      position: absolute; right: 8px; bottom: 4px; font-size: 11px; opacity: .6;
    }
    .ndako-chat .ndako-timeline { display: flex; justify-content: center; margin: 8px 0; }
    .ndako-chat .ndako-timeline > span {
      font-size: 12px; color: #4a4a4a; background: rgba(255,255,255,.85);
      padding: 4px 8px; border-radius: 12px; box-shadow: 0 1px 0 rgba(0,0,0,.06);
    }

    .ndako-chat .ndako-chat__fab {
        pointer-events: auto; width: 56px; height: 56px; border-radius: 50%;
        background: #111827; color: #fff; border: 0; display: inline-flex; align-items: center;
        justify-content: center; box-shadow: 0 10px 20px rgba(0,0,0,.2); cursor: pointer; position: fixed;
        right: 16px; bottom: calc(16px + env(safe-area-inset-bottom, 0px));
    }
    .ndako-chat .ndako-chat__badge {
      position: absolute; top: -4px; right: -4px; background: #e53935; color: #fff; font-weight: 700;
      padding: 2px 6px; border-radius: 999px; font-size: 11px; box-shadow: 0 1px 3px rgba(0,0,0,.25);
    }

    .ndako-chat .ndako-chat__sidebar-header { padding: 10px; display: flex; align-items: center; gap: 8px; }
    .ndako-chat .ndako-chat__title { font-weight: 700; font-size: 16px; }
  `;

  /* --------------------------------- Render -------------------------------- */
  return (
    <div className="ndako-chat" style={{ position: "fixed", right: 16, bottom: 16, zIndex: 2147483000, pointerEvents: "none" }}>
      <style>{globalStyles}</style>

      <Fab open={open} unread={unreadTotal} onClick={() => setOpen((o) => !o)} />

      <Panel open={open} setOpen={setOpen} panelWidth={PANEL_WIDTH}>
        <Sidebar
          isMobile={isMobile}
          panelWidth={PANEL_WIDTH}
          selected={selected}
          convos={convos}
          loading={loadingConvos}
          error={errorConvos}
          onRefresh={() => refreshConversations()}
          onSelect={selectConversation}
          onPin={togglePin}
          onMute={toggleMute}
          onRead={markAsRead}
          onUnread={markAsUnread}
          onClose={closeConversation}
          onDelete={deleteConversation}
          search={search}
          setSearch={setSearch}
          showContacts={showContacts}
          setShowContacts={setShowContacts}
          searchLoading={searchLoading}
          contacts={contacts}
          startWith={startWith}
        />

        <Thread
          isMobile={isMobile}
          panelWidth={PANEL_WIDTH}
          selected={selected}
          current={current}
          groups={todayGroups}
          bodyRef={bodyRef}
          onBack={() => setSelected(null)}
          openConvMenu={openConv}
          hideAllMenus={hideAll}
          openMsgMenu={openMsg}
          pressStart={pressStart}
          pressClear={pressClear}
        >
          <Composer
            selected={selected}
            messageText={messageText}
            setMessageText={setMessageText}
            uploads={uploads}
            setUploads={setUploads}
            uploading={uploading}
            canSend={canSend}
            attachOpen={attachOpen}
            setAttachOpen={setAttachOpen}
            emojiOpen={emojiOpen}
            setEmojiOpen={setEmojiOpen}
            emojiTab={emojiTab}
            setEmojiTab={setEmojiTab}
            startRec={() => {
              if (!selected || recording) return;
              setRecording(true);
              setRecSec(0);
              recTimerRef.current = window.setInterval(() => setRecSec((s) => s + 1), 1000);
            }}
            stopRec={() => {
              if (!recording) return;
              setRecording(false);
              if (recTimerRef.current) clearInterval(recTimerRef.current);
              recTimerRef.current = null;
            }}
            recording={recording}
            recTime={recTime}
            onSend={handleSend}
          />
        </Thread>

        {/* Context Menus (polished, smart position) */}
        <ContextMenu open={convMenu.open} x={convMenu.x} y={convMenu.y} onClose={hideAll}>
          <MenuItem icon={<i className="bi bi-x-circle" />} onClick={() => { if (selected) closeConversation(selected); hideAll(); }}>
            Close conversation
          </MenuItem>
          <div style={{ height: 1, background: "rgba(0,0,0,.06)" }} />
          <MenuItem icon={<i className="bi bi-trash" />} danger onClick={() => { if (selected) deleteConversation(selected); hideAll(); }}>
            Delete conversation
          </MenuItem>
        </ContextMenu>



        <ContextMenu open={msgMenu.open} x={msgMenu.x} y={msgMenu.y} onClose={hideAll}>
          <MenuItem icon={<i className="bi bi-reply" />} onClick={() => { hideAll(); /* TODO reply */ }}>
            Reply
          </MenuItem>
          <MenuItem
            icon={<i className="bi bi-clipboard" />}
            onClick={() => { navigator.clipboard?.writeText(window.getSelection()?.toString() || ""); hideAll(); }}
          >
            Copy
          </MenuItem>
          <MenuItem icon={<i className="bi bi-forward" />} onClick={() => { hideAll(); /* TODO forward */ }}>
            Forward
          </MenuItem>
          <div style={{ height: 1, background: "rgba(0,0,0,.06)" }} />
          <MenuItem icon={<i className="bi bi-trash" />} danger onClick={() => { hideAll(); /* TODO delete msg */ }}>
            Delete message
          </MenuItem>
        </ContextMenu>
      </Panel>
    </div>
  );
}
