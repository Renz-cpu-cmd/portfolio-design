(() => {
  const root = document.documentElement;
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const coarsePointer = window.matchMedia('(pointer: coarse)').matches;
  const nav = document.querySelector('nav');
  const navBox = document.querySelector('.nav-box');
  const navList = document.querySelector('nav ul');
  const navIndicator = document.querySelector('.nav-swipe-indicator');
  const navLinks = navList ? Array.from(navList.querySelectorAll('a')) : [];

  let targetX = 0;
  let targetY = 0;
  let currentX = 0;
  let currentY = 0;
  let navTargetX = 0;
  let navTargetY = 0;
  let navCurrentX = 0;
  let navCurrentY = 0;
  let navVelocityX = 0;
  let navVelocityY = 0;
  let navScale = 0;
  let navScaleTarget = 0;
  let navScaleVelocity = 0;
  let rafId = null;

  const NAV_SPRING_STRENGTH = 0.17;
  const NAV_DAMPING = 0.72;
  const NAV_SCALE_STRENGTH = 0.18;
  const NAV_SCALE_DAMPING = 0.7;

  function positionNavIndicator(link, immediate = false) {
    if (!navIndicator || !navBox || !link) {
      return;
    }

    const navRect = navBox.getBoundingClientRect();
    const linkRect = link.getBoundingClientRect();

    navTargetX = linkRect.left - navRect.left;
    navTargetY = linkRect.top - navRect.top;
    navScaleTarget = 1;

    navIndicator.style.width = `${linkRect.width + 26}px`;
    navIndicator.style.height = `${linkRect.height + 10}px`;

    navTargetX -= 13;
    navTargetY -= 5;

    if (immediate) {
      navCurrentX = navTargetX;
      navCurrentY = navTargetY;
      navScale = 1;
      navVelocityX = 0;
      navVelocityY = 0;
      navScaleVelocity = 0;
      navIndicator.style.transform = `translate(${navCurrentX}px, ${navCurrentY}px) scale(1)`;
    }
  }

  function resetNavIndicator(immediate = false) {
    const activeLink = navLinks.find((link) => link.classList.contains('active'));
    if (activeLink) {
      positionNavIndicator(activeLink, immediate);
    }
  }

  function applyMotion() {
    currentX += (targetX - currentX) * 0.2;
    currentY += (targetY - currentY) * 0.2;

    navVelocityX += (navTargetX - navCurrentX) * NAV_SPRING_STRENGTH;
    navVelocityY += (navTargetY - navCurrentY) * NAV_SPRING_STRENGTH;
    navVelocityX *= NAV_DAMPING;
    navVelocityY *= NAV_DAMPING;
    navCurrentX += navVelocityX;
    navCurrentY += navVelocityY;

    navScaleVelocity += (navScaleTarget - navScale) * NAV_SCALE_STRENGTH;
    navScaleVelocity *= NAV_SCALE_DAMPING;
    navScale += navScaleVelocity;

    root.style.setProperty('--pointer-x', `${currentX.toFixed(2)}px`);
    root.style.setProperty('--pointer-y', `${currentY.toFixed(2)}px`);
    root.style.setProperty('--nav-x', `${navCurrentX.toFixed(2)}px`);
    root.style.setProperty('--nav-y', `${navCurrentY.toFixed(2)}px`);

    if (navIndicator && navScale > 0) {
      navIndicator.style.transform = `translate(${navCurrentX}px, ${navCurrentY}px) scale(${Math.max(navScale, 0.92)})`;
    }

    rafId = requestAnimationFrame(applyMotion);
  }

  function setTargetFromPoint(clientX, clientY) {
    const x = (clientX / window.innerWidth - 0.5) * 130;
    const y = (clientY / window.innerHeight - 0.5) * 130;
    targetX = x;
    targetY = y;
  }

  function setTargetFromScroll() {
    const scrollRatio = Math.min(window.scrollY / Math.max(window.innerHeight, 1), 1.5);
    targetY = scrollRatio * 62;
  }

  if (nav && navList && navIndicator && navLinks.length > 0) {
    navList.addEventListener('mouseleave', resetNavIndicator);
    navLinks.forEach((link) => {
      link.addEventListener('mouseenter', () => positionNavIndicator(link));
      link.addEventListener('focus', () => positionNavIndicator(link));

      link.addEventListener('click', () => {
        navLinks.forEach((item) => item.classList.remove('active'));
        link.classList.add('active');
        positionNavIndicator(link);
      });

      link.addEventListener('mousemove', (event) => {
        const rect = link.getBoundingClientRect();
        const x = (event.clientX - rect.left - rect.width / 2) / rect.width;
        const y = (event.clientY - rect.top - rect.height / 2) / rect.height;

        link.style.setProperty('--link-tilt-x', `${(x * 12).toFixed(2)}deg`);
        link.style.setProperty('--link-tilt-y', `${(-y * 10).toFixed(2)}deg`);
      });

      link.addEventListener('mouseleave', () => {
        link.style.setProperty('--link-tilt-x', '0deg');
        link.style.setProperty('--link-tilt-y', '0deg');
      });
    });

    window.addEventListener('resize', () => resetNavIndicator(true));
    window.addEventListener('load', () => resetNavIndicator(true));
    resetNavIndicator(true);
  }

  if (nav) {
    nav.addEventListener('mousemove', (event) => {
      const rect = nav.getBoundingClientRect();
      const x = (event.clientX - rect.left - rect.width / 2) / rect.width;
      const y = (event.clientY - rect.top - rect.height / 2) / rect.height;

      nav.style.setProperty('--nav-tilt-x', `${(x * 8).toFixed(2)}deg`);
      nav.style.setProperty('--nav-tilt-y', `${(-y * 8).toFixed(2)}deg`);
      nav.style.setProperty('--nav-shift-x', `${(x * 10).toFixed(2)}px`);
      nav.style.setProperty('--nav-shift-y', `${(y * 4).toFixed(2)}px`);
    });

    nav.addEventListener('mouseleave', () => {
      nav.style.setProperty('--nav-tilt-x', '0deg');
      nav.style.setProperty('--nav-tilt-y', '0deg');
      nav.style.setProperty('--nav-shift-x', '0px');
      nav.style.setProperty('--nav-shift-y', '0px');
    });
  }

  if (!reduceMotion && !coarsePointer) {
    window.addEventListener('mousemove', (event) => {
      setTargetFromPoint(event.clientX, event.clientY);
    });
  }

  window.addEventListener('scroll', setTargetFromScroll, { passive: true });

  if (reduceMotion) {
    root.classList.add('reduced-motion');
    root.style.setProperty('--pointer-x', '0px');
    root.style.setProperty('--pointer-y', '0px');
    return;
  }

  setTargetFromScroll();
  rafId = requestAnimationFrame(applyMotion);

  window.addEventListener('beforeunload', () => {
    if (rafId !== null) {
      cancelAnimationFrame(rafId);
    }
  });
})();
