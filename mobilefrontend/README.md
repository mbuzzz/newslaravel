## PWA Configuration Hints

When customizing the Newsten template for PWA, keep the following in mind:

### Prerequisites

- **HTTPS Server:** PWAs require a secure (HTTPS) environment to function correctly.

### 1. Service Worker Setup

- **Comment Out Initial Code:** Before making any customizations, comment out the code in `service-worker.js`. This file catches all the file properties, and disabling it initially helps avoid conflicts during setup.
  
```javascript
// self.addEventListener('install', (event) => {
//     ...
// });
```
- **Enable Later:** Once you're done customizing, uncomment the code to activate the service worker.

### 2. Manifest File Adjustments

- **Change `start_url`:** Update the `start_url` in `manifest.json` to match the entry point of your application.

  ```json
  "start_url": "https://designing-world.com/news10-v2.0/home.html"