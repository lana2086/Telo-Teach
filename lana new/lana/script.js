// Accordion-style FAQ
const faqItems = document.querySelectorAll(".faq-item");

faqItems.forEach((item) => {
  item.addEventListener("click", () => {
    faqItems.forEach((other) => {
      if (other !== item) other.classList.remove("active");
    });
    item.classList.toggle("active");
  });
});


// ===== FADE-IN ANIMATION ON SCROLL =====
const fadeElements = document.querySelectorAll(
  ".hero, .services, .why-us, .portfolio, .testimonials, .team, .faq, .cta, footer"
);

function fadeInOnScroll() {
  fadeElements.forEach((el) => {
    const rect = el.getBoundingClientRect();
    if (rect.top < window.innerHeight - 100) {
      el.classList.add("visible");
    }
  });
}

window.addEventListener("scroll", fadeInOnScroll);
fadeInOnScroll(); // Run on page load

// ===== SMOOTH SCROLL =====
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();
    document.querySelector(this.getAttribute("href")).scrollIntoView({
      behavior: "smooth",
    });
  });
});
const portfolioImages = document.querySelectorAll(".portfolio-grid img");
portfolioImages.forEach((img, index) => {
  img.style.opacity = 0;
  img.style.transition = "opacity 0.8s ease";
  setTimeout(() => {
    img.style.opacity = 1;
  }, index * 200);
});
// ===== FADE-IN ANIMATION FOR SERVICES =====
const serviceBoxes = document.querySelectorAll(".service-box");

function revealServices() {
  serviceBoxes.forEach((box) => {
    const rect = box.getBoundingClientRect();
    if (rect.top < window.innerHeight - 100) {
      box.classList.add("visible");
    }
  });
}

window.addEventListener("scroll", revealServices);
revealServices(); // Run on page load
// ===== FADE-IN FOR PRICING CARDS =====
const pricingCards = document.querySelectorAll(".pricing-card");

function revealPricing() {
  pricingCards.forEach((card) => {
    const rect = card.getBoundingClientRect();
    if (rect.top < window.innerHeight - 100) {
      card.classList.add("visible");
    }
  });
}

window.addEventListener("scroll", revealPricing);
revealPricing();
const pricingItems = document.querySelectorAll(".pricing-item");
function revealPricingModern() {
  pricingItems.forEach((card) => {
    const rect = card.getBoundingClientRect();
    if (rect.top < window.innerHeight - 100) card.classList.add("visible");
  });
}
window.addEventListener("scroll", revealPricingModern);
revealPricingModern();
const contactBoxes = document.querySelectorAll(".contact-info, .contact-form");

function revealContact() {
  contactBoxes.forEach((box) => {
    const rect = box.getBoundingClientRect();
    if (rect.top < window.innerHeight - 100) box.classList.add("visible");
  });
}

window.addEventListener("scroll", revealContact);
revealContact();
// ===== CONTACT FORM TOAST NOTIFICATION =====
const contactForm = document.getElementById("contactForm");
const toast = document.getElementById("toast");

if (contactForm) {
  contactForm.addEventListener("submit", function (e) {
    e.preventDefault();

    // Simulate sending
    setTimeout(() => {
      toast.classList.add("show");
      setTimeout(() => {
        toast.classList.remove("show");
      }, 3000);
      contactForm.reset();
    }, 800);
  });
}
// ===== FADE-IN EFFECT FOR ABOUT PAGE =====
const aboutSections = document.querySelectorAll(".story, .vision-mission, .team, .cta");

function revealAbout() {
  aboutSections.forEach((section) => {
    const rect = section.getBoundingClientRect();
    if (rect.top < window.innerHeight - 100) section.classList.add("visible");
  });
}

window.addEventListener("scroll", revealAbout);
revealAbout();
const revealSections = document.querySelectorAll(".who-we-are, .story, .vision-mission, .values, .team, .join");

function revealOnScroll() {
  revealSections.forEach((sec) => {
    const rect = sec.getBoundingClientRect();
    if (rect.top < window.innerHeight - 100) sec.classList.add("visible");
  });
}

window.addEventListener("scroll", revealOnScroll);
revealOnScroll();
// ===== TIMELINE CAROUSEL SCRIPT =====
const track = document.querySelector(".carousel-track");
const items = document.querySelectorAll(".carousel-item");
const nextBtn = document.querySelector(".carousel-btn.next");
const prevBtn = document.querySelector(".carousel-btn.prev");

if (track && items.length > 0) {
  let index = 0;

  function updateCarousel() {
    const itemWidth = items[0].offsetWidth + 25;
    track.style.transform = `translateX(${-index * itemWidth}px)`;
  }

  nextBtn?.addEventListener("click", () => {
    if (index < items.length - 3) index++;
    updateCarousel();
  });

  prevBtn?.addEventListener("click", () => {
    if (index > 0) index--;
    updateCarousel();
  });

  window.addEventListener("resize", updateCarousel);
}
