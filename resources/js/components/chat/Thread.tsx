import React from "react";
import { Conversation, ID, Message } from "../../data/type";
import { cx, fmtTime } from "../../data/helpers";

export default function Thread({
  isMobile,
  panelWidth,
  selected,
  current,
  groups,
  bodyRef,
  onBack,
  openConvMenu,
  hideAllMenus,
  openMsgMenu,
  pressStart,
  pressClear,
  children,
}: {
  isMobile: boolean;
  panelWidth: number;
  selected: ID | null;
  current: Conversation | null;
  groups: Array<{ key: string; label: string; messages: Message[] }>;
  bodyRef: React.RefObject<HTMLDivElement>;
  onBack: () => void;
  openConvMenu: (e: React.MouseEvent) => void;
  hideAllMenus: () => void;
  openMsgMenu: (x: number, y: number, id: ID, mine: boolean) => void;
  pressStart: (e: React.TouchEvent | React.MouseEvent, id: ID, mine: boolean) => void;
  pressClear: () => void;
  children: React.ReactNode; // composer
}) {
  const width = isMobile ? (selected ? panelWidth : 0) : Math.max(panelWidth - (isMobile ? 0 : 320), 360);

  return (
    <section className={cx("ndako-chat__thread", selected ? "is-open" : "is-hidden")} style={{ width }}>
      <header className="ndako-chat__header">
        <div className="ndako-chat__left" style={{ display: "flex", alignItems: "center", gap: 10 }}>
          {isMobile && selected && (
            <button type="button" className="ndako-chat__back" title="Back" onClick={onBack}
              style={{ border: 0, background: "transparent", padding: 4, cursor: "pointer" }}>
              <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
                <path d="M15 18l-6-6 6-6" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
              </svg>
            </button>
          )}

          <div className="ndako-chat__title ndako-chat__title--rich" style={{ display: "flex", alignItems: "center", gap: 10 }}>
            {selected && current?.other?.avatar ? (
              <img src={current.other.avatar} alt="" style={{ width: 34, height: 34, borderRadius: "50%", objectFit: "cover" }} />
            ) : selected ? (
              <div style={{ width: 34, height: 34, borderRadius: "50%", display: "grid", placeItems: "center", background: "#cfd8dc", fontWeight: 700 }}>
                {(current?.other?.name || "C").slice(0, 1).toUpperCase()}
              </div>
            ) : null}

            <div>
              <div style={{ fontWeight: 700 }}>{selected ? current?.other?.name || "Chat" : "Ndako Messenger"}</div>
              {selected && <div style={{ fontSize: 12, opacity: .7 }}><span style={{ display: "none" }}>typing…</span></div>}
            </div>
          </div>
        </div>

        <div style={{ display: "flex", alignItems: "center", gap: 6 }}>
          {selected && (
            <>
              <button type="button" title="Voice call" style={{ border: 0, background: "transparent", padding: 6, cursor: "pointer" }}>
                <i className="bi bi-telephone" />
              </button>
              <button type="button" title="Video call" style={{ border: 0, background: "transparent", padding: 6, cursor: "pointer" }}>
                <i className="bi bi-camera-video" />
              </button>
              <button type="button" title="Contact info" style={{ border: 0, background: "transparent", padding: 6, cursor: "pointer" }}>
                <i className="bi bi-info-circle" />
              </button>
              <button type="button" title="Search in chat" style={{ border: 0, background: "transparent", padding: 6, cursor: "pointer" }}>
                <i className="bi bi-search" />
              </button>
            </>
          )}
          {/* Close button moved to Panel header via parent (kept minimal here) */}
        </div>
      </header>

      {/* Empty placeholder: expanded on desktop, hidden on small screens */}
      {!isMobile && !selected ? (
        <div style={{ flex: 1, display: "grid", placeItems: "center", padding: 24, color: "#555" }}>
          <div style={{ maxWidth: 420, textAlign: "center" }}>
            <div style={{ fontWeight: 800, fontSize: 22, marginBottom: 8 }}>Ndako Messenger</div>
            <div>Select a conversation or start a new one to begin chatting.</div>
          </div>
        </div>
      ) : null}

      {selected && (
        <>
          <div
            className="ndako-chat__body"
            id="ndako-body"
            ref={bodyRef}
            onContextMenu={(e) => {
              if ((e.target as HTMLElement).closest(".ndako-chat__bubble")) return;
              e.preventDefault();
              openConvMenu(e);
            }}
            onClick={() => hideAllMenus()}
          >
            {/* time chips & messages */}
            {groups.map((g) => (
              <div key={g.key}>
                <div className="ndako-timeline"><span>{g.label}</span></div>
                {g.messages.map((m) => {
                  const isMine = m.sender_type === "user";
                  const timeOnly = fmtTime(m.created_at);
                  return (
                    <div className={cx("ndako-chat__msg", isMine ? "is-user" : "is-agent")} key={m.id}>
                      <div
                        className={cx("ndako-chat__bubble", isMine ? "b-user" : "b-peer")}
                        onContextMenu={(e) => {
                          e.preventDefault();
                          openMsgMenu(e.clientX, e.clientY, m.id, isMine);
                        }}
                        onTouchStart={(e) => pressStart(e, m.id, isMine)}
                        onTouchEnd={() => pressClear()}
                        onTouchMove={() => pressClear()}
                      >
                        {m.body && <div className="ndako-chat__text">{m.body}</div>}

                        {m.attachments?.length > 0 && (
                          <div style={{ marginTop: 6 }}>
                            {m.attachments.map((a) => (
                              <a key={a.id} href={a.url || "#"} target="_blank" rel="noreferrer"
                                style={{ display: "inline-block", background: "rgba(0,0,0,.04)", padding: "4px 6px", borderRadius: 6, marginRight: 6 }}>
                                {a.name}
                              </a>
                            ))}
                          </div>
                        )}

                        <div className="ndako-chat__time">{timeOnly}</div>
                      </div>
                    </div>
                  );
                })}
              </div>
            ))}
          </div>

          {children}
        </>
      )}
    </section>
  );
}
