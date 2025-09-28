import React from 'react';
import { createRoot } from 'react-dom/client';
import ChatWidget from './components/chat/ChatWidget';

const el = document.getElementById('ndako-chat');

if (el) {
  const root = createRoot(el as HTMLElement);
  root.render(<ChatWidget />);
}
