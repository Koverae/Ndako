import React from "react";

export default function Fab({ open, unread, onClick }: { open: boolean; unread: number; onClick: () => void }) {
  return (
    <button
      type="button"
      className="ndako-chat__fab"
      aria-label="Open chat"
      aria-expanded={open}
      onClick={onClick}
      style={{ position: "fixed" }}
    >
      {unread > 0 && <span className="ndako-chat__badge">{unread > 99 ? "99+" : unread}</span>}
      <svg viewBox="0 0 24 24" width="26" height="26" aria-hidden="true">
        <path
          d="M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM6 9h12M6 13h8"
          fill="none"
          stroke="currentColor"
          strokeWidth="2"
          strokeLinecap="round"
        />
      </svg>
    </button>
  );
}
