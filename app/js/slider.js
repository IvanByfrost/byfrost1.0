document.addEventListener("DOMContentLoaded", () => {
  const sliderTabs = document.querySelectorAll(".slider-tab");
  const sliderIndicator = document.querySelector(".slider-indicator"); // corregido

  console.log("Tabs encontrados:", sliderTabs.length);
  console.log("Indicador:", sliderIndicator);

  const updateIndicator = (tab, index) => {
    sliderIndicator.style.transform = `translateX(${tab.offsetLeft - 20}px)`;
    sliderIndicator.style.width = `${tab.getBoundingClientRect().width}px`;
  }

  // Inicializar Swiper después de la definición de los elementos
  const swiper = new Swiper(".slider-container", {
    effect: "fade",
    speed: 1300,
    autoplay: {
      // delay: 3000,
    },
    navigation: {
      nextEl: ".next", // usa clases o ids válidos
      prevEl: ".prev",
    },
    on: {
      slideChange: () => {
        const currentTabIndex = [...sliderTabs].indexOf(sliderTabs[swiper.activeIndex]);
        updateIndicator(sliderTabs[swiper.activeIndex], currentTabIndex);
      }
    }
  });

  // Manejador de evento de clic en los tabs
  sliderTabs.forEach((tab, index) => { 
    tab.addEventListener("click", () => {
      swiper.slideTo(index);
      updateIndicator(tab, index);
    });
  });

  // Verificar valores de Swiper
  console.log("Swiper activo:", swiper.activeIndex); // Mover este log aquí
});

