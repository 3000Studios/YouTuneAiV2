'use client'

import { useState } from 'react'
import Link from 'next/link'
import { useAuth } from '@/lib/auth'
import { Bars3Icon, XMarkIcon } from '@heroicons/react/24/outline'
import { motion } from 'framer-motion'

export function Header() {
  const [isMenuOpen, setIsMenuOpen] = useState(false)
  const { user, logout } = useAuth()

  const navigation = [
    { name: 'Features', href: '#features' },
    { name: 'Pricing', href: '#pricing' },
    { name: 'AI Tools', href: '/ai-tools' },
    { name: 'Analytics', href: '/analytics' },
    { name: 'Creator Network', href: '/creators' },
  ]

  return (
    <header className="relative z-50 w-full">
      <nav className="container mx-auto px-6 py-4">
        <div className="flex items-center justify-between">
          {/* Logo */}
          <Link href="/" className="flex items-center space-x-2 group">
            <div className="relative">
              <div className="w-10 h-10 bg-gradient-to-br from-diamond-cyan to-primary-500 rounded-lg flex items-center justify-center shadow-lg diamond-glow">
                <span className="text-white font-bold text-xl">Y</span>
              </div>
              <div className="absolute -top-1 -right-1 w-3 h-3 bg-diamond-cyan rounded-full animate-pulse"></div>
            </div>
            <div className="flex flex-col">
              <span className="font-montserrat font-bold text-lg text-gradient">
                YouTuneAi
              </span>
              <span className="text-xs text-diamond-platinum/70 -mt-1">
                V2 • Boss Man's System
              </span>
            </div>
          </Link>

          {/* Desktop Navigation */}
          <div className="hidden lg:flex items-center space-x-8">
            {navigation.map((item) => (
              <Link
                key={item.name}
                href={item.href}
                className="nav-link text-sm font-medium sparkle"
              >
                {item.name}
              </Link>
            ))}
          </div>

          {/* User Menu or Auth Buttons */}
          <div className="hidden lg:flex items-center space-x-4">
            {user ? (
              <div className="flex items-center space-x-4">
                <Link
                  href="/dashboard"
                  className="text-diamond-platinum hover:text-diamond-cyan transition-colors"
                >
                  Dashboard
                </Link>
                <div className="flex items-center space-x-2 card-glass px-4 py-2 rounded-full">
                  <div className="w-8 h-8 bg-gradient-to-br from-diamond-cyan to-primary-500 rounded-full flex items-center justify-center">
                    <span className="text-white text-sm font-semibold">
                      {user.name?.[0] || user.email[0].toUpperCase()}
                    </span>
                  </div>
                  <span className="text-sm text-diamond-platinum">
                    {user.name || user.email}
                  </span>
                  <span className="text-xs bg-diamond-cyan text-black px-2 py-1 rounded-full font-medium">
                    {user.subscriptionTier}
                  </span>
                </div>
                <button
                  onClick={logout}
                  className="text-diamond-platinum/70 hover:text-red-400 transition-colors text-sm"
                >
                  Logout
                </button>
              </div>
            ) : (
              <div className="flex items-center space-x-4">
                <Link
                  href="/auth/login"
                  className="text-diamond-platinum hover:text-diamond-cyan transition-colors text-sm font-medium"
                >
                  Login
                </Link>
                <Link
                  href="/auth/register"
                  className="btn-primary text-sm"
                >
                  Start Free Trial
                </Link>
              </div>
            )}
          </div>

          {/* Mobile menu button */}
          <button
            onClick={() => setIsMenuOpen(!isMenuOpen)}
            className="lg:hidden p-2 text-diamond-platinum hover:text-diamond-cyan transition-colors"
          >
            {isMenuOpen ? (
              <XMarkIcon className="w-6 h-6" />
            ) : (
              <Bars3Icon className="w-6 h-6" />
            )}
          </button>
        </div>

        {/* Mobile Navigation */}
        {isMenuOpen && (
          <motion.div
            initial={{ opacity: 0, height: 0 }}
            animate={{ opacity: 1, height: 'auto' }}
            exit={{ opacity: 0, height: 0 }}
            className="lg:hidden mt-4 card-glass rounded-2xl overflow-hidden"
          >
            <div className="px-6 py-4 space-y-4">
              {navigation.map((item) => (
                <Link
                  key={item.name}
                  href={item.href}
                  className="block text-diamond-platinum hover:text-diamond-cyan transition-colors py-2 border-b border-white/10 last:border-b-0"
                  onClick={() => setIsMenuOpen(false)}
                >
                  {item.name}
                </Link>
              ))}
              
              <div className="pt-4 border-t border-white/10">
                {user ? (
                  <div className="space-y-4">
                    <Link
                      href="/dashboard"
                      className="block text-diamond-platinum hover:text-diamond-cyan transition-colors"
                      onClick={() => setIsMenuOpen(false)}
                    >
                      Dashboard
                    </Link>
                    <div className="flex items-center space-x-2">
                      <div className="w-8 h-8 bg-gradient-to-br from-diamond-cyan to-primary-500 rounded-full flex items-center justify-center">
                        <span className="text-white text-sm font-semibold">
                          {user.name?.[0] || user.email[0].toUpperCase()}
                        </span>
                      </div>
                      <div>
                        <div className="text-sm text-diamond-platinum">
                          {user.name || user.email}
                        </div>
                        <div className="text-xs text-diamond-platinum/70">
                          {user.subscriptionTier} Plan
                        </div>
                      </div>
                    </div>
                    <button
                      onClick={() => {
                        logout()
                        setIsMenuOpen(false)
                      }}
                      className="w-full text-left text-red-400 hover:text-red-300 transition-colors"
                    >
                      Logout
                    </button>
                  </div>
                ) : (
                  <div className="space-y-4">
                    <Link
                      href="/auth/login"
                      className="block text-diamond-platinum hover:text-diamond-cyan transition-colors"
                      onClick={() => setIsMenuOpen(false)}
                    >
                      Login
                    </Link>
                    <Link
                      href="/auth/register"
                      className="block btn-primary text-center"
                      onClick={() => setIsMenuOpen(false)}
                    >
                      Start Free Trial
                    </Link>
                  </div>
                )}
              </div>
            </div>
          </motion.div>
        )}
      </nav>
    </header>
  )
}