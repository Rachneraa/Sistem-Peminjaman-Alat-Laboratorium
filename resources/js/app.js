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

window.addEventListener('scroll', () => {
	document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50)
})

document.querySelectorAll('.navbar__links a').forEach((link) => {
	link.addEventListener('click', (e) => {
		e.preventDefault()
		lenis.scrollTo(link.getAttribute('href'))
	})
})

const burger = document.getElementById('burger')
const navLinks = document.querySelector('.navbar__links')

if (burger && navLinks) {
	burger.addEventListener('click', () => {
		navLinks.classList.toggle('open')
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





