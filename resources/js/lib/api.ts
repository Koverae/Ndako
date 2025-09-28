// Axios instance with Sanctum CSRF cookie helper and small wrappers
import axios from 'axios';
import { Conversation, ID, Message, Paginated } from '../data/type';

/* ------------------------------ API Utilities ----------------------------- */

export const api = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: { 'X-Requested-With': 'XMLHttpRequest' },
});


export async function ensureCsrf() {
    // Only needed if using session + Sanctum SPA
    try { await axios.get('/sanctum/csrf-cookie', { withCredentials: true }); } catch {}
}

export const ChatAPI = {
  async conversations(page = 1) {
    const { data } = await api.get<Paginated<Conversation>>(`/chat/conversations`, { params: { page } });
    return data;
  },
  async messages(conversationId: ID, page = 1, per_page = 40) {
    const { data } = await api.get<Paginated<Message>>(
      `/chat/conversations/${conversationId}/messages`,
      { params: { page, per_page } }
    );
    return data;
  },
  async startConversation(participant_type: "user" | "guest", participant_id: ID) {
    const { data } = await api.post<{ data: Conversation }>(`/chat/conversations`, {
      participant_type,
      participant_id,
    });
    return data.data;
  },
  async send(conversationId: ID, body: string, files: File[]) {
    const form = new FormData();
    if (body) form.append("body", body);
    files.forEach((f) => form.append("files[]", f));
    const { data } = await api.post<{ data: Message }>(
      `/chat/conversations/${conversationId}/message`,
      form,
      { headers: { "Content-Type": "multipart/form-data" } }
    );
    return data.data;
  },
  async markRead(conversationId: ID) {
    await api.post(`/chat/conversations/${conversationId}/read`);
  },
  async markUnread(conversationId: ID) {
    await api.post(`/chat/conversations/${conversationId}/unread`);
  },
  async close(conversationId: ID) {
    await api.post(`/chat/conversations/${conversationId}/close`);
  },
  async destroy(conversationId: ID) {
    await api.delete(`/chat/conversations/${conversationId}`);
  },
  async searchContacts(q: string) {
    const { data } = await api.get<{ data: Array<{ type: "user" | "guest"; id: ID; name: string; label: string; avatar?: string | null }> }>(
      `/chat/contacts/search`,
      { params: { q } }
    );
    return data.data ?? [];
  },
};
