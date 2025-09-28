import React from "react";
import { Conversation, ID } from "../../data/type";
import ConvoRow from "./ConvoRow";

export default function Sidebar({
  isMobile,
  panelWidth,
  selected,
  convos,
  loading,
  error,
  onRefresh,
  onSelect,
  onPin,
  onMute,
  onRead,
  onUnread,
  onClose,
  onDelete,
  search, setSearch,
  showContacts, setShowContacts,
  searchLoading,
  contacts,
  startWith,
}: {
  isMobile: boolean;
  panelWidth: number;
  selected: ID | null;
  convos: Conversation[];
  loading: boolean;
  error: string | null;
  onRefresh: () => void;
  onSelect: (id: ID) => void;
  onPin: (id: ID) => void;
  onMute: (id: ID) => void;
  onRead: (id: ID) => void;
  onUnread: (id: ID) => void;
  onClose: (id: ID) => void;
  onDelete: (id: ID) => void;
  search: string; setSearch: (v: string) => void;
  showContacts: boolean; setShowContacts: (v: boolean) => void;
  searchLoading: boolean;
  contacts: Array<{ type: "user" | "guest"; id: ID; name: string; label: string; avatar?: string | null }>;
  startWith: (c: { type: "user" | "guest"; id: ID }) => void;
}) {
  const SIDEBAR_WIDTH = isMobile ? (selected ? 0 : Math.min(panelWidth, 360)) : 320;

  return (
    <aside className="ndako-chat__sidebar" style={{ width: SIDEBAR_WIDTH, maxWidth: SIDEBAR_WIDTH }}>
      <div className="ndako-chat__sidebar-header">
        <div className="ndako-chat__title">Chats</div>
        <div style={{ flex: 1 }} />
        <button type="button" className="ndako-chat__ghost" onClick={onRefresh}>Refresh</button>
        <button
          type="button"
          className="ndako-chat__tiny"
          title="New chat"
          onClick={() => {
            setShowContacts(true);
            setTimeout(() => document.getElementById("ndako-search")?.focus(), 0);
          }}
        >
          +
        </button>
      </div>

      {/* Contact Search */}
      <div className="mt-2 ndako-chat__search" style={{ padding: "0 10px 10px", position: "relative" }}>
        <div className="ndako-input-wrap" style={{ position: "relative" }}>
          <input
            id="ndako-search"
            type="text"
            placeholder="Search contacts..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            style={{
              width: "100%", borderRadius: 10, border: "1px solid rgba(0,0,0,.12)",
              padding: "8px 10px", outline: "none",
            }}
          />
          {searchLoading && <span className="ndako-spin" />}
        </div>

        {showContacts && (
          <>
            <div
              className="ndako-chat__search-pop"
              style={{
                position: "absolute", left: 10, right: 10, top: "100%", marginTop: 8,
                background: "#fff", borderRadius: 12, maxHeight: 260, overflowY: "auto",
                boxShadow: "0 12px 24px rgba(0,0,0,.16)", border: "1px solid rgba(0,0,0,.06)", zIndex: 10,
              }}
            >
              {contacts.length === 0 && !searchLoading && (
                <div className="ndako-chat__empty" style={{ padding: 10 }}>No matching contacts</div>
              )}
              {contacts.map((c) => (
                <button
                  type="button"
                  key={`${c.type}-${c.id}`}
                  className="ndako-chat__contact"
                  onClick={(e) => { e.preventDefault(); startWith(c); }}
                  style={{ width: "100%", textAlign: "left", display: "flex", gap: 10, alignItems: "center", padding: 10, background: "transparent", border: 0, cursor: "pointer" }}
                >
                  {c.avatar ? (
                    <img src={c.avatar} alt="" style={{ width: 36, height: 36, borderRadius: "50%", objectFit: "cover" }} />
                  ) : (
                    <div style={{ width: 36, height: 36, borderRadius: "50%", background: "#e0e0e0", display: "grid", placeItems: "center", fontWeight: 700 }}>
                      {c.type[0].toUpperCase()}
                    </div>
                  )}
                  <div>
                    <div style={{ fontWeight: 600 }}>{c.name}</div>
                    <div style={{ fontSize: 12, opacity: .7 }}>{c.label}</div>
                  </div>
                </button>
              ))}
            </div>

            {searchLoading && (
              <div
                className="ndako-chat__search-pop"
                style={{
                  position: "absolute", left: 10, right: 10, top: "100%", marginTop: 8,
                  background: "#fff", borderRadius: 12, padding: 10,
                  boxShadow: "0 12px 24px rgba(0,0,0,.16)", border: "1px solid rgba(0,0,0,.06)", zIndex: 10,
                }}
              >
                {Array.from({ length: 4 }).map((_, i) => (
                  <div key={i} style={{ height: 10, background: "rgba(0,0,0,.06)", borderRadius: 6, marginBottom: 8 }} />
                ))}
              </div>
            )}
          </>
        )}
      </div>

      {/* Conversation list */}
      <div
        className="ndako-chat__convos"
        onClick={() => window.dispatchEvent(new CustomEvent("ndako-close-all-swipes"))}
        style={{ padding: "4px 6px 10px", flex: 1, minHeight: 0 }}
      >
        {loading ? (
          <div className="ndako-skeleton">
            {Array.from({ length: 6 }).map((_, i) => (
              <div key={i} style={{ height: 56, background: "rgba(0,0,0,.06)", borderRadius: 10, margin: 8 }} />
            ))}
          </div>
        ) : error ? (
          <div className="ndako-chat__empty" style={{ padding: 10 }}>{error}</div>
        ) : convos.length === 0 ? (
          <div className="ndako-chat__empty" style={{ padding: 10 }}>No conversations yet.</div>
        ) : (
          convos.map((c) => (
            <ConvoRow
              key={c.id}
              convo={c}
              active={selected === c.id}
              onSelect={onSelect}
              onPin={onPin}
              onMute={onMute}
              onRead={onRead}
              onUnread={onUnread}
              onClose={onClose}
              onDelete={onDelete}
            />
          ))
        )}
      </div>
    </aside>
  );
}
