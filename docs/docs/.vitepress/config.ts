import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  base: '/',
  title: "Ndako Documentation",
  description: "Ndako Documentation website",
  lastUpdated: true,
  head: [
    // ['link', { rel: 'icon', href: 'https://github.com/bavix/laravel-wallet/assets/5111255/f48a8e79-8a9d-469a-b056-b3d04835992d' }]
  ],

  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
    search: {
        provider: 'local',
    },

    logo: '/logo-doc.png',
    nav: [

      { text: 'Home', link: '/' },
      { text: 'Guide', link: '/v1/user-docs/introduction' },
      {
        text: 'v1',
        items: [
          {
            text: 'v1',
            link: '/v1/user-docs/introduction'
          }
        ]
      },
    ],

    sidebar: [
        {
          text: '📘 User Docs',
          items: [
            // Get Started
            { text: "Get Started",
                items: [
                    { text: "What's Ndako?", link: '/v1/user-docs/introduction/'},
                    { text: 'How It Works', link: '/v1/user-docs/introduction/how-it-works' },
                    { text: 'Creating Your Account', link: '/v1/user-docs/introduction/create-account' },
                    { text: 'Dashboard Overview', link: '/v1/user-docs/introduction/dashboard-overview' },
                    { text: 'Ndako Essentials',
                        items: [
                            { text: 'Search, filter, and group records', link: '/v1/user-docs/introduction/essential/search-others' },
                            { text: 'Export and import data', link: '/v1/user-docs/introduction/essential/export-import' },
                            // { text: 'In-app purchases (IAP)', link: '/v1/user-docs/introduction/essential/iap' },
                        ],
                        collapsed: true,
                    },
                ],
                collapsed: true
            },
            // Property Management
            {
            text: 'Property Management',
                collapsed: true,
                items: [
                    { text: 'Add a Property', link: '/v1/user-docs/property-management/add-property' },
                    { text: 'Units and Rooms', link: '/v1/user-docs/property-management/units-rooms' },
                    { text: 'Property Settings', link: '/v1/user-docs/property-management/property-settings' }
                ]
            },
            // Guests & Tenants
            {
            text: 'Guests & Tenants',
                collapsed: true,
                items: [
                    { text: 'Check-in / Check-out', link: '/v1/user-docs/guest-tenant/check-in-out' },
                    { text: 'Communication Tools', link: '/v1/user-docs/guest-tenant/communication' },
                    // { text: 'Guest Portal', link: '/v1/user-docs/guest-tenant/guest-portal' }
                ]
            },
            // Reservations & Bookings
            {
              text: 'Reservations & Bookings',
              collapsed: true,
              items: [
                { text: 'Create a Booking', link: '/v1/user-docs/reservations/create-booking' },
                { text: 'Calendar View', link: '/v1/user-docs/reservations/calendar-view' },
                { text: 'Booking Policies', link: '/v1/user-docs/reservations/policies' },
                { text: 'Booking Settings', link: '/v1/user-docs/reservations/booking-settings' }
              ]
            },
            // Financials
            {
              text: 'Financials',
              collapsed: true,
              items: [
                { text: 'Payments & Invoices', link: '/v1/user-docs/financials/payments-invoices' },
                { text: 'Track Expenses', link: '/v1/user-docs/financials/track-expenses' },
                { text: 'Basic Reports', link: '/v1/user-docs/financials/reports' }
              ]
            },
            // Account Settings
            {
              text: 'Account Settings',
              collapsed: true,
              items: [
                { text: 'Profile & Security', link: '/v1/user-docs/settings/profile-security' },
                { text: 'Notifications', link: '/v1/user-docs/settings/notifications' },
                { text: 'Team & Permissions', link: '/v1/user-docs/settings/team-permissions' }
              ]
            },
            // FAQs & Help
            {
              text: 'FAQs & Help',
              collapsed: true,
              items: [
                { text: 'Common Questions', link: '/v1/user-docs/faqs/common-questions' },
                { text: 'Contact Support', link: '/v1/user-docs/faqs/contact-support' }
              ]
            },
            // On-Premise
            {
              text: 'On-Premise',
              collapsed: true,
              items: [
                { text: 'Requirements', link: '/v1/user-docs/on-premise/requirements' },
                { text: 'Donwload Ndako', link: '/v1/user-docs/on-premise/download' },
                { text: 'Installation', link: '/v1/user-docs/on-premise/installation' },
                { text: 'Update', link: '/v1/user-docs/on-premise/update' },
                { text: 'Debugging Errors', link: '/v1/user-docs/on-premise/debug-error' }
              ]
            }

          ],
        //   collapsed: true,
        },
      ],

    socialLinks: [
      { icon: 'github', link: 'https://github.com/Koverae' },
      {
        icon: {
          svg: '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><title>telegram</title> <path d="M22.122 10.040c0.006-0 0.014-0 0.022-0 0.209 0 0.403 0.065 0.562 0.177l-0.003-0.002c0.116 0.101 0.194 0.243 0.213 0.403l0 0.003c0.020 0.122 0.031 0.262 0.031 0.405 0 0.065-0.002 0.129-0.007 0.193l0-0.009c-0.225 2.369-1.201 8.114-1.697 10.766-0.21 1.123-0.623 1.499-1.023 1.535-0.869 0.081-1.529-0.574-2.371-1.126-1.318-0.865-2.063-1.403-3.342-2.246-1.479-0.973-0.52-1.51 0.322-2.384 0.221-0.23 4.052-3.715 4.127-4.031 0.004-0.019 0.006-0.040 0.006-0.062 0-0.078-0.029-0.149-0.076-0.203l0 0c-0.052-0.034-0.117-0.053-0.185-0.053-0.045 0-0.088 0.009-0.128 0.024l0.002-0.001q-0.198 0.045-6.316 4.174c-0.445 0.351-1.007 0.573-1.619 0.599l-0.006 0c-0.867-0.105-1.654-0.298-2.401-0.573l0.074 0.024c-0.938-0.306-1.683-0.467-1.619-0.985q0.051-0.404 1.114-0.827 6.548-2.853 8.733-3.761c1.607-0.853 3.47-1.555 5.429-2.010l0.157-0.031zM15.93 1.025c-8.302 0.020-15.025 6.755-15.025 15.060 0 8.317 6.742 15.060 15.060 15.060s15.060-6.742 15.060-15.060c0-8.305-6.723-15.040-15.023-15.060h-0.002q-0.035-0-0.070 0z"></path></svg>'
        },
        link: 'https://t.me/koverae',
        ariaLabel: 'Telegram Group'
      }
    ],

    footer: {
      copyright: 'Copyright © 2025 <a href="https://github.com/Koverae">Koverae Technologies</a>'
    }
  },
})
