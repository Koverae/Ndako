
/* ---------------------------------- Types --------------------------------- */

export type ID = number;

export type Attachment = {
  id: ID;
  url: string | null;
  name: string;
  type: string;
  size: number;
};

export type Message = {
  id: ID;
  conversation_id: ID;
  sender_type: "user" | "guest";
  sender_id: ID;
  body: string | null;
  attachments: Attachment[];
  created_at: string; // ISO8601
};

export type Participant = {
  type: "user" | "guest";
  id: ID;
  name: string;
  avatar?: string | null;
};

export type Conversation = {
  id: ID;
  subject: string | null;
  status: "open" | "closed" | string;
  other: Participant | null;
  last?: {
    id: ID;
    body: string | null;
    sender_type: "user" | "guest";
    sender_id: ID;
    created_at: string;
  } | null;
  unread: number;
  updated_at: string; // ISO8601

  pinned?: boolean; // client-only
  muted?: boolean;  // client-only
};

export type Paginated<T> = {
  data: T[];
  meta?: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
};
