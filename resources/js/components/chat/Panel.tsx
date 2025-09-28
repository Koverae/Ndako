import React from "react";

export default function Panel({
  open,
  setOpen,
  panelWidth,
  children,
}: {
  open: boolean;
  setOpen: (v: boolean) => void;
  panelWidth: number;
  children: React.ReactNode;
}) {
  return (
    <div
      className="ndako-chat__panel ndako-elevate"
      role="dialog"
      aria-label="Chat"
      style={{
        display: open ? "flex" : "none",
        width: panelWidth,
        maxWidth: Math.min(panelWidth, window.innerWidth * 0.96),
      }}
    >
      {children}
      {/* Close cross (global) */}
      <button
        type="button"
        aria-label="Close"
        onClick={() => setOpen(false)}
        style={{
          position: "absolute", top: 8, right: 8, border: 0, background: "transparent",
          padding: 6, cursor: "pointer",
        }}
      >
        <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
          <path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
        </svg>
      </button>
    </div>
  );
}
