import React from "react";
import { ID } from "../../data/type";

export default function Composer({
  selected,
  messageText, setMessageText,
  uploads, setUploads,
  uploading,
  canSend,
  attachOpen, setAttachOpen,
  emojiOpen, setEmojiOpen,
  emojiTab, setEmojiTab,
  startRec, stopRec, recording, recTime,
  onSend,
}: {
  selected: ID | null;
  messageText: string; setMessageText: (v: string) => void;
  uploads: File[]; setUploads: (v: File[]) => void;
  uploading: boolean;
  canSend: boolean;
  attachOpen: boolean; setAttachOpen: (v: boolean) => void;
  emojiOpen: boolean; setEmojiOpen: (v: boolean) => void;
  emojiTab: "smileys" | "gestures" | "symbols"; setEmojiTab: (v: "smileys" | "gestures" | "symbols") => void;
  startRec: () => void; stopRec: () => void; recording: boolean; recTime: string;
  onSend: (e: React.FormEvent) => void;
}) {
  const EMO_SMILEYS =
    "😀 😃 😄 😁 😆 😅 😂 🤣 😊 🙂 🙃 😉 😍 😘 😗 😚 😙 😋 😛 😜 🤪 😝 🫠 🤗 🤭 🤫 🤔 🤐 🤨 😐 😑 😶 🫥 😏 😒 🙄 😬 😮‍💨 🤥 😌 😴 🤤 😪 😮 😯 😲 😳 🥵 🥶 🥴 🤯 😕 😟 🙁 ☹️".split(" ");
  const EMO_GESTURES =
    "👍 👎 👏 🙌 🙏 🤝 💪 👌 🤌 🤏 ✌️ 🤘 🤙 👋 🤚 ✋ 🖐️ 🖖 👈 👉 👆 👇 ☝️ ✊ 👊 🤛 🤜".split(" ");
  const EMO_SYMBOLS =
    "❤️ 🧡 💛 💚 💙 💜 🤎 🖤 🤍 💔 ❣️ 💕 💞 💓 💗 💖 💘 💝 💟 ✨ ⭐ 🌟 🔥 🎉 ✅ ❌ ⚠️ ❗ ❓ 💯 🕒 📌 📎".split(" ");
  const currentEmojis = emojiTab === "smileys" ? EMO_SMILEYS : emojiTab === "gestures" ? EMO_GESTURES : EMO_SYMBOLS;

  return (
    <>
      <form className="ndako-chat__inputbar" onSubmit={onSend}>
        {/* Attach */}
        <button
          type="button"
          title="Attach"
          onClick={() => { setAttachOpen(!attachOpen); if (!attachOpen) setEmojiOpen(false); }}
          disabled={uploading}
          style={{ border: 0, background: "transparent", cursor: "pointer" }}
        >
          <i className="bi bi-paperclip" />
        </button>

        {/* Attach palette */}
        {attachOpen && (
          <div
            onClick={(e) => e.stopPropagation()}
            style={{
              position: "absolute", bottom: 56, left: 8, background: "#fff",
              border: "1px solid rgba(0,0,0,.08)", borderRadius: 12, padding: 10,
              boxShadow: "0 10px 24px rgba(0,0,0,.18)", display: "grid",
              gridTemplateColumns: "repeat(4, 1fr)", gap: 10, zIndex: 5,
            }}
          >
            <label style={{ textAlign: "center", cursor: "pointer" }}>
              <input type="file" multiple accept="image/*,video/*" hidden
                onChange={(e) => setUploads([...(uploads || []), ...(Array.from(e.target.files || []))])} />
              <i className="bi bi-image" /><span style={{ display: "block", fontSize: 12, marginTop: 4 }}>Photos</span>
            </label>
            <label style={{ textAlign: "center", cursor: "pointer" }}>
              <input type="file" multiple accept=".pdf,.doc,.docx,application/*" hidden
                onChange={(e) => setUploads([...(uploads || []), ...(Array.from(e.target.files || []))])} />
              <i className="bi bi-file-earmark-text" /><span style={{ display: "block", fontSize: 12, marginTop: 4 }}>Document</span>
            </label>
            <label style={{ textAlign: "center", cursor: "pointer" }}>
              <input type="file" accept="image/*" hidden
                onChange={(e) => setUploads([...(uploads || []), ...(Array.from(e.target.files || []))])} />
              <i className="bi bi-camera" /><span style={{ display: "block", fontSize: 12, marginTop: 4 }}>Camera</span>
            </label>
            <label style={{ textAlign: "center", cursor: "pointer" }}>
              <input type="file" hidden />
              <i className="bi bi-mic" /><span style={{ display: "block", fontSize: 12, marginTop: 4 }}>Audio</span>
            </label>
          </div>
        )}

        {/* Emoji */}
        <button
          type="button"
          title="Emoji"
          onClick={() => { setEmojiOpen(!emojiOpen); if (!emojiOpen) setAttachOpen(false); }}
          style={{ border: 0, background: "transparent", cursor: "pointer" }}
        >
          <i className="bi bi-emoji-smile" />
        </button>

        {emojiOpen && (
          <div
            onClick={(e) => e.stopPropagation()}
            style={{
              position: "absolute", bottom: 56, right: 8, width: 260, background: "#fff",
              border: "1px solid rgba(0,0,0,.08)", borderRadius: 12, padding: 8,
              boxShadow: "0 10px 24px rgba(0,0,0,.18)", zIndex: 5,
            }}
          >
            <div style={{ display: "flex", gap: 6, marginBottom: 8 }}>
              <button type="button" onClick={(e) => { e.preventDefault(); setEmojiTab("smileys"); }}
                style={{ border: "1px solid rgba(0,0,0,.1)", background: emojiTab === "smileys" ? "#f5f5f5" : "#fff", padding: "4px 8px", borderRadius: 8, cursor: "pointer" }}>
                😊
              </button>
              <button type="button" onClick={(e) => { e.preventDefault(); setEmojiTab("gestures"); }}
                style={{ border: "1px solid rgba(0,0,0,.1)", background: emojiTab === "gestures" ? "#f5f5f5" : "#fff", padding: "4px 8px", borderRadius: 8, cursor: "pointer" }}>
                👍
              </button>
              <button type="button" onClick={(e) => { e.preventDefault(); setEmojiTab("symbols"); }}
                style={{ border: "1px solid rgba(0,0,0,.1)", background: emojiTab === "symbols" ? "#f5f5f5" : "#fff", padding: "4px 8px", borderRadius: 8, cursor: "pointer" }}>
                ✨
              </button>
            </div>
            <div style={{ display: "grid", gridTemplateColumns: "repeat(8, 1fr)", gap: 6, maxHeight: 180, overflowY: "auto" }}>
              {currentEmojis.map((emo) => (
                <button
                  type="button"
                  key={emo}
                  onClick={() => setMessageText(`${messageText} ${emo}`)}
                  aria-label={`Insert ${emo}`}
                  style={{ border: "1px solid rgba(0,0,0,.08)", background: "#fff", borderRadius: 8, padding: "6px 0", cursor: "pointer" }}
                >
                  {emo}
                </button>
              ))}
            </div>
          </div>
        )}

        {/* Input */}
        <input
          type="text"
          className="ndako-chat__input"
          placeholder="Type a message…"
          value={messageText}
          onChange={(e) => setMessageText(e.target.value)}
          disabled={!selected}
        />

        {/* Mic vs Send */}
        {canSend ? (
          <button type="submit" title="Send" disabled={!selected || uploading} style={{ border: 0, background: "transparent", cursor: "pointer" }}>
            <i className="bi bi-send-fill" />
            {uploading && <span className="ndako-btnspin" />}
          </button>
        ) : (
          <button
            type="button"
            title="Voice note"
            onMouseDown={(e) => { e.preventDefault(); startRec(); }}
            onMouseUp={(e) => { e.preventDefault(); stopRec(); }}
            onTouchStart={(e) => { e.preventDefault(); startRec(); }}
            onTouchEnd={(e) => { e.preventDefault(); stopRec(); }}
            style={{ border: 0, background: "transparent", cursor: "pointer" }}
          >
            {!recording ? (
              <i className="bi bi-mic-fill" />
            ) : (
              <>
                <span style={{ display: "inline-block", width: 8, height: 8, borderRadius: "50%", background: "#e53935", marginRight: 6 }} />
                <span>{recTime}</span>
              </>
            )}
          </button>
        )}
      </form>

      {/* Upload progress */}
      {uploading && (
        <div style={{ padding: 8, background: "rgba(255,255,255,.7)", borderTop: "1px solid rgba(0,0,0,.06)" }}>
          Uploading… <span />
        </div>
      )}

      {/* Attach preview chips */}
      {uploads.length > 0 && (
        <div style={{ padding: "6px 10px", display: "flex", gap: 6, flexWrap: "wrap", background: "#f0f2f5", borderTop: "1px dashed rgba(0,0,0,.12)" }}>
          {uploads.map((f, i) => (
            <span key={`${f.name}-${i}`} style={{ background: "#fff", border: "1px solid rgba(0,0,0,.06)", borderRadius: 16, padding: "4px 8px", fontSize: 12 }}>
              {f.name}
            </span>
          ))}
        </div>
      )}
    </>
  );
}
