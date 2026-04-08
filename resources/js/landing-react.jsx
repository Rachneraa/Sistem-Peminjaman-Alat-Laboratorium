import React from 'react';
import { createRoot } from 'react-dom/client';
import LandingParallaxApp from './landing/LandingParallaxApp';
import '../css/landing-react.css';

const rootElement = document.getElementById('landing-react-root');
const globalProps = window.__LANDING_REACT_PROPS__;

if (rootElement) {
    const safeProps = globalProps && typeof globalProps === 'object' ? globalProps : {};

    try {
        createRoot(rootElement).render(
            React.createElement(LandingParallaxApp, safeProps)
        );
    } catch (error) {
        console.error('Failed to mount landing React app:', error);
        rootElement.innerHTML =
            '<section style="padding:24px;color:#ffffff;background:#0f1022;min-height:60vh;display:grid;place-items:center;text-align:center;">' +
            '<div><h2 style="margin:0 0 8px;">Halaman sedang diperbarui</h2><p style="margin:0;opacity:.85;">Silakan refresh halaman beberapa saat lagi.</p></div>' +
            '</section>';
    }
}
