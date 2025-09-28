import React, { useEffect, useRef, useState } from "react";
import { Conversation, ID } from "../../data/type";
import { cx, humanListTime } from "../../data/helpers";



type ConvoRowProps = {
  convo: Conversation;
  active: boolean;
  onSelect: (id: ID) => void;
  onPin: (id: ID) => void;
  onMute: (id: ID) => void;
  onRead: (id: ID) => void;
  onUnread: (id: ID) => void;
  onClose: (id: ID) => void;
  onDelete: (id: ID) => void;
};


/* ------------------------------ Swipe Controller -------------------------- */
function useSwipeRail(widthProvider: () => number) {
  const [translateX, setTranslateX] = useState(0);
  const [opened, setOpened] = useState(false);
  const draggingRef = useRef(false);
  const startXRef = useRef(0);
  const railWidthRef = useRef(0);

  useEffect(() => {
    const update = () => (railWidthRef.current = widthProvider());
    update();
    window.addEventListener("resize", update);
    return () => window.removeEventListener("resize", update);
  }, [widthProvider]);

  const start = (e: React.MouseEvent | React.TouchEvent) => {
    const p = "touches" in e ? e.touches[0] : (e as React.MouseEvent);
    draggingRef.current = true;
    startXRef.current = p.clientX - translateX;
    window.dispatchEvent(new CustomEvent("ndako-close-all-swipes"));
  };

  const move = (e: React.MouseEvent | React.TouchEvent) => {
    if (!draggingRef.current) return;
    const p = "touches" in e ? e.touches[0] : (e as React.MouseEvent);
    let next = p.clientX - startXRef.current;
    const min = -railWidthRef.current;
    next = Math.min(20, Math.max(next, min));
    setTranslateX(next);
  };

  const end = () => {
    if (!draggingRef.current) return;
    draggingRef.current = false;
    const threshold = -railWidthRef.current * 0.3;
    if (translateX >= 0) close();
    else translateX <= threshold ? open() : close();
  };

  const cancel = () => { if (draggingRef.current) end(); };
  const open = () => { setTranslateX(-railWidthRef.current); setOpened(true); };
  const close = () => { setTranslateX(0); setOpened(false); };

  useEffect(() => {
    const h = () => close();
    window.addEventListener("ndako-close-all-swipes", h as any);
    return () => window.removeEventListener("ndako-close-all-swipes", h as any);
  }, []);

  return { translateX, opened, open, close, start, move, end, cancel, railWidth: () => railWidthRef.current };
}

export default function ConvoRow({ convo: c, active, onSelect, onPin, onMute, onRead, onUnread, onClose, onDelete }: ConvoRowProps) {
  const rail = useSwipeRail(() => (window.innerWidth < 460 ? 208 : 264));
  return (
    <div className="ndako-convo-wrap" onKeyDown={(e) => e.key === "Escape" && rail.close()}>
      {/* Action rail */}
      <div className={cx("ndako-actions", rail.opened && "is-open")} style={{ width: `${rail.railWidth()}px` }} aria-hidden="true">
        <button type="button" className="ndako-act ndako-act--pin" title={c.pinned ? "Unpin" : "Pin"}
          onClick={(e) => { e.stopPropagation(); onPin(c.id); rail.close(); }}>
          <i className="bi bi-pin-angle-fill" />
        </button>
        <button type="button" className="ndako-act ndako-act--mute" title={c.muted ? "Unmute" : "Mute"}
          onClick={(e) => { e.stopPropagation(); onMute(c.id); rail.close(); }}>
          <i className="bi bi-bell-slash-fill" />
        </button>
        {c.unread > 0 ? (
          <button type="button" className="ndako-act ndako-act--read" title="Mark as read"
            onClick={(e) => { e.stopPropagation(); onRead(c.id); rail.close(); }}>
            <i className="bi bi-check2-all" />
          </button>
        ) : (
          <button type="button" className="ndako-act ndako-act--read" title="Mark as unread"
            onClick={(e) => { e.stopPropagation(); onUnread(c.id); rail.close(); }}>
            <i className="bi bi-dot" />
          </button>
        )}
        <button type="button" className="ndako-act ndako-act--close" title="Close conversation"
          onClick={(e) => { e.stopPropagation(); onClose(c.id); rail.close(); }}>
          <i className="bi bi-x-circle-fill" />
        </button>
        <button type="button" className="ndako-act ndako-act--danger" title="Delete"
          onClick={(e) => { e.stopPropagation(); onDelete(c.id); rail.close(); }}>
          <i className="bi bi-trash-fill" />
        </button>
      </div>

      {/* Swipeable tile */}
      <div
        className={cx("ndako-convo", active && "is-active")}
        role="button"
        tabIndex={0}
        onTouchStart={rail.start}
        onTouchMove={rail.move}
        onTouchEnd={rail.end}
        onMouseDown={rail.start}
        onMouseMove={rail.move}
        onMouseUp={rail.end}
        onMouseLeave={rail.cancel}
        style={{ transform: `translateX(${rail.translateX}px)` }}
        onClick={() => { rail.close(); onSelect(c.id); }}
      >
        <button type="button" className="ndako-convo__avatar" onClick={(e) => { e.stopPropagation(); rail.close(); onSelect(c.id); }}>
          {c.other?.avatar ? (
            <img src={c.other.avatar} alt="" />
          ) : (
            <div className="ndako-convo__fallback">{(c.other?.name || "C").slice(0, 1).toUpperCase()} </div>
          )}
          <span className={cx("ndako-convo__chip", (c.other?.type ?? "user") === "user" ? "chip-user" : "chip-guest")}>
            {(c.other?.type ?? "u").slice(0, 1).toUpperCase()}
          </span>
        </button>

        <button type="button" className="ndako-convo__main" onClick={(e) => { e.stopPropagation(); rail.close(); onSelect(c.id); }}>
          <div className="ndako-convo__row1">
            <div className="ndako-convo__title">
              {c.pinned && <span className="ndako-pin">📌</span>}
              {c.other?.name || "Chat"}
            </div>
            <div className="ndako-convo__time">{humanListTime(c.updated_at)}</div>
          </div>
          <div className="ndako-convo__row2">
            <div className="ndako-convo__last">{c.last?.body || "—"}</div>
            <div className="ndako-convo__badges">
              {c.muted && <span className="ndako-convo__mute" title="Muted">🔕</span>}
              {c.unread > 0 && <span className="ndako-convo__unread">{c.unread}</span>}
            </div>
          </div>
        </button>

        <div className="ndako-convo__kebab kebab-inline">
          <button type="button" className="ndako-kebab__btn" title="More" aria-label="More options"
            onClick={(e) => { e.stopPropagation(); rail.open(); }}>
            ⋮
          </button>
        </div>
      </div>
    </div>
  );
}
