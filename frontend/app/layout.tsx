import './globals.css'
import { Inter, Montserrat } from 'next/font/google'
import { Providers } from './providers'
import { Toaster } from 'react-hot-toast'

const inter = Inter({ subsets: ['latin'], variable: '--font-inter' })
const montserrat = Montserrat({ subsets: ['latin'], variable: '--font-montserrat' })

export const metadata = {
  title: 'YouTuneAi V2 - Infinite Revenue AI Platform',
  description: 'Boss Man\'s ultimate AI-powered content creation and monetization platform. Generate infinite revenue with automated social media, AI content, and advanced analytics.',
  keywords: 'AI content creation, social media automation, monetization platform, YouTube automation, content marketing, artificial intelligence',
  authors: [{ name: '3000Studios' }],
  creator: '3000Studios',
  publisher: '3000Studios',
  openGraph: {
    type: 'website',
    locale: 'en_US',
    url: 'https://youtuneai.com',
    title: 'YouTuneAi V2 - Infinite Revenue AI Platform',
    description: 'Boss Man\'s ultimate AI-powered content creation and monetization platform.',
    siteName: 'YouTuneAi V2',
    images: [
      {
        url: '/images/og-image.jpg',
        width: 1200,
        height: 630,
        alt: 'YouTuneAi V2 Platform',
      },
    ],
  },
  twitter: {
    card: 'summary_large_image',
    title: 'YouTuneAi V2 - Infinite Revenue AI Platform',
    description: 'Boss Man\'s ultimate AI-powered content creation and monetization platform.',
    images: ['/images/twitter-card.jpg'],
    creator: '@YouTuneAi',
  },
  robots: {
    index: true,
    follow: true,
    googleBot: {
      index: true,
      follow: true,
      'max-video-preview': -1,
      'max-image-preview': 'large',
      'max-snippet': -1,
    },
  },
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <html lang="en" className={`${inter.variable} ${montserrat.variable}`}>
      <head>
        <link rel="icon" href="/favicon.ico" />
        <link rel="apple-touch-icon" href="/apple-touch-icon.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="theme-color" content="#00ffff" />
        
        {/* Google Analytics */}
        {process.env.NEXT_PUBLIC_GOOGLE_ANALYTICS_ID && (
          <>
            <script
              async
              src={`https://www.googletagmanager.com/gtag/js?id=${process.env.NEXT_PUBLIC_GOOGLE_ANALYTICS_ID}`}
            />
            <script
              dangerouslySetInnerHTML={{
                __html: `
                  window.dataLayer = window.dataLayer || [];
                  function gtag(){dataLayer.push(arguments);}
                  gtag('js', new Date());
                  gtag('config', '${process.env.NEXT_PUBLIC_GOOGLE_ANALYTICS_ID}', {
                    page_title: document.title,
                    page_location: window.location.href,
                  });
                `,
              }}
            />
          </>
        )}
        
        {/* Structured Data */}
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{
            __html: JSON.stringify({
              "@context": "https://schema.org",
              "@type": "SoftwareApplication",
              "name": "YouTuneAi V2",
              "description": "AI-powered content creation and monetization platform",
              "url": "https://youtuneai.com",
              "applicationCategory": "BusinessApplication",
              "operatingSystem": "Web",
              "offers": {
                "@type": "Offer",
                "price": "29.99",
                "priceCurrency": "USD",
                "priceValidUntil": "2024-12-31"
              },
              "publisher": {
                "@type": "Organization",
                "name": "3000Studios"
              }
            }),
          }}
        />
      </head>
      <body className="antialiased">
        <Providers>
          <div className="particles-bg" id="particles-js"></div>
          {children}
          <Toaster 
            position="top-right"
            toastOptions={{
              duration: 4000,
              style: {
                background: 'rgba(0, 0, 0, 0.8)',
                color: '#fff',
                border: '1px solid rgba(0, 255, 255, 0.3)',
                borderRadius: '12px',
                backdropFilter: 'blur(10px)',
              },
              success: {
                iconTheme: {
                  primary: '#00ffff',
                  secondary: '#000',
                },
              },
              error: {
                iconTheme: {
                  primary: '#ef4444',
                  secondary: '#fff',
                },
              },
            }}
          />
        </Providers>
        
        {/* Particle.js Script */}
        <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
        <script
          dangerouslySetInnerHTML={{
            __html: `
              if (typeof particlesJS !== 'undefined') {
                particlesJS('particles-js', {
                  particles: {
                    number: { value: 80, density: { enable: true, value_area: 800 } },
                    color: { value: '#00ffff' },
                    shape: { type: 'circle' },
                    opacity: { value: 0.5, random: false },
                    size: { value: 3, random: true },
                    line_linked: {
                      enable: true,
                      distance: 150,
                      color: '#00ffff',
                      opacity: 0.2,
                      width: 1
                    },
                    move: {
                      enable: true,
                      speed: 2,
                      direction: 'none',
                      random: false,
                      straight: false,
                      out_mode: 'out',
                      bounce: false
                    }
                  },
                  interactivity: {
                    detect_on: 'canvas',
                    events: {
                      onhover: { enable: true, mode: 'repulse' },
                      onclick: { enable: true, mode: 'push' },
                      resize: true
                    }
                  },
                  retina_detect: true
                });
              }
            `,
          }}
        />
      </body>
    </html>
  )
}