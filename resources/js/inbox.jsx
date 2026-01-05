import './bootstrap.js';
import '../css/inbox.css';
import React from 'react';
import { createRoot } from 'react-dom/client';
import InboxPage from './components/InboxPage.jsx';

if (document.getElementById('inbox-root')) {
  const container = document.getElementById('inbox-root');
  const root = createRoot(container);
  root.render(<InboxPage />);
}
