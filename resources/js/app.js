import './bootstrap'

import Lenis from 'lenis'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

const lenis = new Lenis({
	duration: 1.4,
	easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
})

function raf(time) {
	lenis.raf(time)
	ScrollTrigger.update()
	requestAnimationFrame(raf)
}
requestAnimationFrame(raf)

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches
const isMobile = window.innerWidth <= 768
const navbar = document.getElementById('navbar')

window.addEventListener('scroll', () => {
	if (navbar) {
		navbar.classList.toggle('scrolled', window.scrollY > 50)
	}
})

document.querySelectorAll('.navbar__links a').forEach((link) => {
	link.addEventListener('click', (e) => {
		const href = link.getAttribute('href') || ''
		if (!href || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return

		if (href.startsWith('#')) {
			e.preventDefault()
			lenis.scrollTo(href)
			return
		}

		const targetUrl = new URL(href, window.location.origin)
		if (targetUrl.origin === window.location.origin && targetUrl.pathname === window.location.pathname && targetUrl.hash) {
			e.preventDefault()
			lenis.scrollTo(targetUrl.hash)
			if (burger && navLinks) {
				navLinks.classList.remove('open')
				burger.setAttribute('aria-expanded', 'false')
			}
		}
	})
})

const burger = document.getElementById('burger')
const navLinks = document.querySelector('.navbar__links')

if (burger && navLinks) {
	burger.addEventListener('click', () => {
		navLinks.classList.toggle('open')
		burger.setAttribute('aria-expanded', navLinks.classList.contains('open') ? 'true' : 'false')
	})
}

const heroTl = gsap.timeline({ defaults: { ease: 'expo.out' } })
heroTl
	.from('.hero__badge', { opacity: 0, y: 30, duration: 0.8 })
	.from('.hero__title', { opacity: 0, y: 60, duration: 1.2 }, '-=0.4')
	.from('.hero__sub', { opacity: 0, y: 40, duration: 1 }, '-=0.8')
	.from('.hero__cta', { opacity: 0, y: 30, duration: 0.8 }, '-=0.7')
	.from('.stat-card', { opacity: 0, y: 40, stagger: 0.15, duration: 0.9 }, '-=0.8')
	.from('.hero__list-card', { opacity: 0, x: 40, duration: 0.9 }, '-=0.7')
	.from('.floater', { opacity: 0, scale: 0.5, stagger: 0.2, duration: 1.5 }, '-=1')

if (!prefersReducedMotion && !isMobile) {
	gsap.to('.blob-1', {
		y: -180,
		ease: 'none',
		scrollTrigger: {
			trigger: '#hero',
			start: 'top top',
			end: 'bottom top',
			scrub: 1.5,
		},
	})

	gsap.to('.hero__grid', {
		y: -60,
		ease: 'none',
		scrollTrigger: {
			trigger: '#hero',
			start: 'top top',
			end: 'bottom top',
			scrub: 2,
		},
	})

	gsap.to('.ring-1', {
		y: -100,
		ease: 'none',
		scrollTrigger: {
			trigger: '#hero',
			start: 'top top',
			end: 'bottom top',
			scrub: 1,
		},
	})
}

gsap.from('.step-card', {
	opacity: 0,
	y: 80,
	stagger: 0.2,
	ease: 'power3.out',
	duration: 1,
	scrollTrigger: {
		trigger: '.steps__grid',
		start: 'top 80%',
	},
})

gsap.from('.katalog-card', {
	opacity: 0,
	y: 60,
	scale: 0.95,
	stagger: 0.12,
	ease: 'power3.out',
	duration: 0.9,
	scrollTrigger: {
		trigger: '.katalog__grid',
		start: 'top 80%',
	},
})

if (!prefersReducedMotion && !isMobile) {
	gsap.to('.katalog__bg-ring', {
		y: -150,
		ease: 'none',
		scrollTrigger: {
			trigger: '#katalog',
			start: 'top bottom',
			end: 'bottom top',
			scrub: 2,
		},
	})
}

document.querySelectorAll('.accordion-header').forEach((btn) => {
	btn.addEventListener('click', () => {
		const item = btn.parentElement
		const isOpen = item.classList.contains('open')

		document.querySelectorAll('.accordion-item').forEach((i) => i.classList.remove('open'))

		if (!isOpen) item.classList.add('open')
	})
})

gsap.from('.accordion-item', {
	opacity: 0,
	x: -60,
	stagger: 0.1,
	ease: 'power3.out',
	duration: 0.9,
	scrollTrigger: {
		trigger: '.accordion-list',
		start: 'top 80%',
	},
})

if (document.querySelector('.page-hero')) {
	gsap.from('.page-hero__title', { opacity: 0, y: 50, duration: 1, ease: 'expo.out' })
	gsap.from('.page-hero__sub', { opacity: 0, y: 30, duration: 1, delay: 0.2, ease: 'expo.out' })

	gsap.to('.page-hero__blob', {
		y: -100,
		ease: 'none',
		scrollTrigger: {
			trigger: '.page-hero',
			start: 'top top',
			end: 'bottom top',
			scrub: 1.5,
		},
	})

	gsap.to('.page-ring-1', {
		y: -80,
		ease: 'none',
		scrollTrigger: {
			trigger: '.page-hero',
			start: 'top top',
			end: 'bottom top',
			scrub: 1,
		},
	})
}

if (document.querySelector('.alat-grid')) {
	gsap.from('.filter-bar', {
		opacity: 0,
		y: -20,
		duration: 0.6,
		ease: 'power2.out',
		scrollTrigger: { trigger: '.filter-bar', start: 'top 90%' },
	})

	gsap.from('.alat-card', {
		opacity: 0,
		y: 60,
		scale: 0.96,
		stagger: {
			each: 0.08,
			from: 'start',
		},
		ease: 'power3.out',
		duration: 0.8,
		scrollTrigger: {
			trigger: '.alat-grid',
			start: 'top 85%',
		},
	})

		const filterForm = document.getElementById('toolsFilterForm')
		const searchInput = document.getElementById('searchInput')
		const categorySelect = document.getElementById('categorySelect')
		const sortSelect = document.getElementById('sortSelect')
		const resultsContainer = document.getElementById('alatResults')

		if (filterForm && resultsContainer) {
			const syncUrl = (url) => {
				window.history.pushState({}, '', url)
			}

			const getRequestUrl = () => {
				const formData = new FormData(filterForm)
				const params = new URLSearchParams()

				for (const [key, value] of formData.entries()) {
					if (typeof value === 'string' && value.trim() !== '') {
						params.set(key, value.trim())
					}
				}

				const queryString = params.toString()
				return `${filterForm.action}${queryString ? `?${queryString}` : ''}`
			}

			const updateResults = async (url, pushState = true) => {
				const response = await fetch(url, {
					headers: {
						'X-Requested-With': 'XMLHttpRequest',
					},
					credentials: 'same-origin',
				})

				if (!response.ok) {
					throw new Error('Gagal memuat hasil alat.')
				}

				const html = await response.text()
				const doc = new DOMParser().parseFromString(html, 'text/html')
				const newResults = doc.getElementById('alatResults')

				if (!newResults) {
					throw new Error('Konten hasil tidak ditemukan.')
				}

				resultsContainer.innerHTML = newResults.innerHTML
				if (pushState) {
					syncUrl(url)
				}
			}

			let searchTimer = null
			const submitFilters = () => {
				window.clearTimeout(searchTimer)
				searchTimer = window.setTimeout(() => {
					updateResults(getRequestUrl()).catch(() => {
						window.location.href = getRequestUrl()
					})
				}, 220)
			}

			filterForm.addEventListener('submit', (event) => {
				event.preventDefault()
				updateResults(getRequestUrl()).catch(() => {
					window.location.href = getRequestUrl()
				})
			})

			searchInput?.addEventListener('input', submitFilters)
			categorySelect?.addEventListener('change', () => updateResults(getRequestUrl()).catch(() => {
				window.location.href = getRequestUrl()
			}))
			sortSelect?.addEventListener('change', () => updateResults(getRequestUrl()).catch(() => {
				window.location.href = getRequestUrl()
			}))

			resultsContainer.addEventListener('click', (event) => {
				const link = event.target.closest('.alat-pagination a')
				if (!link) return

				event.preventDefault()
				updateResults(link.href).catch(() => {
					window.location.href = link.href
				})
			})

			window.addEventListener('popstate', () => {
				updateResults(window.location.href, false).catch(() => {
					window.location.reload()
				})
			})
		}
}





