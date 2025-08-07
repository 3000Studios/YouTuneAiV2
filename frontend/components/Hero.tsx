'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'
import { motion } from 'framer-motion'
import { 
  SparklesIcon, 
  PlayIcon, 
  ChartBarIcon,
  BoltIcon,
  CurrencyDollarIcon,
  StarIcon
} from '@heroicons/react/24/outline'

export function Hero() {
  const [currentStat, setCurrentStat] = useState(0)
  const [isPlaying, setIsPlaying] = useState(false)

  const stats = [
    { value: '$2.8M+', label: 'Revenue Generated' },
    { value: '50K+', label: 'Active Users' },
    { value: '1.2M+', label: 'Content Created' },
    { value: '99.9%', label: 'Uptime' }
  ]

  const features = [
    { icon: SparklesIcon, text: 'AI Content Generation' },
    { icon: BoltIcon, text: 'Instant Social Media Automation' },
    { icon: CurrencyDollarIcon, text: 'Multiple Revenue Streams' },
    { icon: ChartBarIcon, text: 'Advanced Analytics' }
  ]

  useEffect(() => {
    const interval = setInterval(() => {
      setCurrentStat((prev) => (prev + 1) % stats.length)
    }, 2000)
    return () => clearInterval(interval)
  }, [])

  const handleVoiceCommand = () => {
    setIsPlaying(true)
    
    if ('webkitSpeechRecognition' in window) {
      const recognition = new (window as any).webkitSpeechRecognition()
      recognition.continuous = false
      recognition.interimResults = false
      recognition.lang = 'en-US'
      
      recognition.onstart = () => {
        console.log('Voice recognition started')
      }
      
      recognition.onresult = (event: any) => {
        const command = event.results[0][0].transcript.toLowerCase()
        
        if (command.includes('hello') || command.includes('hi')) {
          const msg = new SpeechSynthesisUtterance('Hello! Welcome to YouTuneAi V2, Boss Man\'s ultimate revenue generation system!')
          window.speechSynthesis.speak(msg)
        } else if (command.includes('features') || command.includes('what can you do')) {
          const msg = new SpeechSynthesisUtterance('I can generate infinite content, automate your social media, process payments, and maximize your revenue with AI!')
          window.speechSynthesis.speak(msg)
        } else if (command.includes('pricing') || command.includes('cost')) {
          const msg = new SpeechSynthesisUtterance('Our plans start at just $29.99 per month for unlimited AI content generation and social media automation!')
          window.speechSynthesis.speak(msg)
        }
      }
      
      recognition.onerror = (event: any) => {
        console.error('Voice recognition error:', event.error)
      }
      
      recognition.onend = () => {
        setIsPlaying(false)
      }
      
      recognition.start()
    } else {
      // Fallback for browsers without speech recognition
      const msg = new SpeechSynthesisUtterance('Hello! Welcome to YouTuneAi V2, Boss Man\'s ultimate revenue generation system!')
      window.speechSynthesis.speak(msg)
      setIsPlaying(false)
    }
  }

  return (
    <section className="relative min-h-screen flex items-center justify-center overflow-hidden">
      {/* Background Video Effect */}
      <div className="absolute inset-0 opacity-20">
        <div className="w-full h-full bg-gradient-to-br from-diamond-cyan/10 via-transparent to-primary-500/10"></div>
      </div>

      <div className="container mx-auto px-6 py-20 relative z-10">
        <div className="max-w-6xl mx-auto">
          <div className="grid lg:grid-cols-2 gap-16 items-center">
            
            {/* Left Content */}
            <motion.div
              initial={{ opacity: 0, x: -50 }}
              animate={{ opacity: 1, x: 0 }}
              transition={{ duration: 0.8 }}
              className="space-y-8"
            >
              {/* Announcement Badge */}
              <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 0.2 }}
                className="inline-flex items-center space-x-2 bg-gradient-to-r from-diamond-cyan/20 to-primary-500/20 rounded-full px-6 py-3 border border-diamond-cyan/30"
              >
                <StarIcon className="w-5 h-5 text-diamond-cyan" />
                <span className="text-sm font-medium text-diamond-platinum">
                  🚀 V2.0 Now Live - Boss Man's Ultimate System
                </span>
              </motion.div>

              {/* Main Headline */}
              <div className="space-y-4">
                <motion.h1
                  initial={{ opacity: 0, y: 20 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: 0.3 }}
                  className="text-4xl lg:text-6xl xl:text-7xl font-montserrat font-black leading-tight"
                >
                  <span className="text-gradient">Infinite Revenue</span>
                  <br />
                  <span className="text-white">AI Platform</span>
                </motion.h1>

                <motion.p
                  initial={{ opacity: 0, y: 20 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: 0.4 }}
                  className="text-xl lg:text-2xl text-diamond-platinum/90 font-light leading-relaxed max-w-2xl"
                >
                  Boss Man's revolutionary system that generates{' '}
                  <span className="text-diamond-cyan font-semibold">unlimited AI content</span>,{' '}
                  automates social media, processes payments, and creates{' '}
                  <span className="text-diamond-cyan font-semibold">infinite revenue streams</span>{' '}
                  while you sleep.
                </motion.p>
              </div>

              {/* Feature Pills */}
              <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 0.5 }}
                className="flex flex-wrap gap-3"
              >
                {features.map((feature, index) => (
                  <div
                    key={index}
                    className="flex items-center space-x-2 bg-white/5 backdrop-blur-sm rounded-full px-4 py-2 border border-white/10 hover:bg-white/10 transition-all duration-300"
                  >
                    <feature.icon className="w-4 h-4 text-diamond-cyan" />
                    <span className="text-sm text-diamond-platinum font-medium">
                      {feature.text}
                    </span>
                  </div>
                ))}
              </motion.div>

              {/* CTA Buttons */}
              <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 0.6 }}
                className="flex flex-col sm:flex-row gap-4 pt-4"
              >
                <Link
                  href="/auth/register"
                  className="btn-primary text-lg px-8 py-4 font-bold shadow-2xl"
                >
                  Start Infinite Revenue
                  <SparklesIcon className="inline w-5 h-5 ml-2" />
                </Link>
                
                <button
                  onClick={handleVoiceCommand}
                  className={`btn-secondary text-lg px-8 py-4 font-bold flex items-center justify-center ${
                    isPlaying ? 'animate-pulse bg-diamond-cyan text-black' : ''
                  }`}
                  disabled={isPlaying}
                >
                  <PlayIcon className={`w-5 h-5 mr-2 ${isPlaying ? 'animate-spin' : ''}`} />
                  {isPlaying ? 'Listening...' : 'Try Voice Demo'}
                </button>
              </motion.div>

              {/* Voice Instructions */}
              <motion.p
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                transition={{ delay: 0.7 }}
                className="text-sm text-diamond-platinum/70 italic"
              >
                💡 Try saying: "Hello", "What can you do?", or "Show me pricing"
              </motion.p>
            </motion.div>

            {/* Right Content - Interactive Dashboard Preview */}
            <motion.div
              initial={{ opacity: 0, x: 50 }}
              animate={{ opacity: 1, x: 0 }}
              transition={{ duration: 0.8, delay: 0.3 }}
              className="relative"
            >
              {/* Main Dashboard Card */}
              <div className="card-glass diamond-glow relative overflow-hidden">
                <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-diamond-cyan to-primary-500"></div>
                
                {/* Header */}
                <div className="p-6 border-b border-white/10">
                  <div className="flex items-center justify-between">
                    <div>
                      <h3 className="text-lg font-bold text-white">
                        Revenue Dashboard
                      </h3>
                      <p className="text-sm text-diamond-platinum/70">
                        Real-time performance metrics
                      </p>
                    </div>
                    <div className="flex items-center space-x-2">
                      <div className="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                      <span className="text-sm text-green-400 font-medium">LIVE</span>
                    </div>
                  </div>
                </div>

                {/* Stats */}
                <div className="p-6">
                  <motion.div
                    key={currentStat}
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5 }}
                    className="text-center mb-6"
                  >
                    <div className="text-4xl font-bold text-gradient mb-2">
                      {stats[currentStat].value}
                    </div>
                    <div className="text-diamond-platinum/70">
                      {stats[currentStat].label}
                    </div>
                  </motion.div>

                  {/* Mini Chart Visualization */}
                  <div className="space-y-4">
                    <div className="flex justify-between text-sm">
                      <span className="text-diamond-platinum/70">Content Generated</span>
                      <span className="text-diamond-cyan font-semibold">+285%</span>
                    </div>
                    <div className="w-full bg-gray-800 rounded-full h-2">
                      <motion.div
                        className="bg-gradient-to-r from-diamond-cyan to-primary-500 h-2 rounded-full"
                        initial={{ width: 0 }}
                        animate={{ width: '85%' }}
                        transition={{ duration: 2, delay: 1 }}
                      />
                    </div>
                    
                    <div className="flex justify-between text-sm">
                      <span className="text-diamond-platinum/70">Social Automation</span>
                      <span className="text-green-400 font-semibold">+412%</span>
                    </div>
                    <div className="w-full bg-gray-800 rounded-full h-2">
                      <motion.div
                        className="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full"
                        initial={{ width: 0 }}
                        animate={{ width: '92%' }}
                        transition={{ duration: 2, delay: 1.2 }}
                      />
                    </div>
                    
                    <div className="flex justify-between text-sm">
                      <span className="text-diamond-platinum/70">Revenue Growth</span>
                      <span className="text-yellow-400 font-semibold">+650%</span>
                    </div>
                    <div className="w-full bg-gray-800 rounded-full h-2">
                      <motion.div
                        className="bg-gradient-to-r from-yellow-400 to-yellow-600 h-2 rounded-full"
                        initial={{ width: 0 }}
                        animate={{ width: '96%' }}
                        transition={{ duration: 2, delay: 1.4 }}
                      />
                    </div>
                  </div>
                </div>
              </div>

              {/* Floating Elements */}
              <motion.div
                animate={{ y: [0, -10, 0] }}
                transition={{ repeat: Infinity, duration: 3 }}
                className="absolute -top-4 -right-4 w-16 h-16 bg-gradient-to-br from-diamond-cyan to-primary-500 rounded-2xl flex items-center justify-center shadow-2xl diamond-glow"
              >
                <SparklesIcon className="w-8 h-8 text-white" />
              </motion.div>

              <motion.div
                animate={{ y: [0, 10, 0] }}
                transition={{ repeat: Infinity, duration: 4, delay: 1 }}
                className="absolute -bottom-4 -left-4 w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-xl"
              >
                <CurrencyDollarIcon className="w-6 h-6 text-white" />
              </motion.div>
            </motion.div>

          </div>
        </div>
      </div>
    </section>
  )
}