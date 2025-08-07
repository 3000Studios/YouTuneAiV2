import { Header } from '@/components/Header'
import { Hero } from '@/components/Hero'
import { Features } from '@/components/Features'
import { Pricing } from '@/components/Pricing'
import { Testimonials } from '@/components/Testimonials'
import { CTA } from '@/components/CTA'
import { Footer } from '@/components/Footer'

export default function HomePage() {
  return (
    <main className="min-h-screen">
      <Header />
      
      <div className="relative">
        {/* Marble background overlay */}
        <div className="marble-bg absolute inset-0 opacity-10 pointer-events-none" />
        
        {/* Content sections */}
        <div className="relative z-10">
          <Hero />
          <Features />
          <Pricing />
          <Testimonials />
          <CTA />
        </div>
      </div>
      
      <Footer />
    </main>
  )
}