// Scroll to Element Function
function scrollToElement(elementId) {
  const element = document.getElementById(elementId);
  if (element) {
    element.scrollIntoView({ behavior: 'smooth' });
  }
}

// Typing Animation for Hero Section
document.addEventListener('DOMContentLoaded', () => {
  const typingText = document.getElementById('typing-text');
  const cursor = document.getElementById('cursor');
  const fullText = 'BSIT Student & Developer';
  let currentIndex = 0;
  let cursorVisible = true;

  // Typing animation
  const typingInterval = setInterval(() => {
    if (currentIndex <= fullText.length) {
      typingText.textContent = fullText.slice(0, currentIndex);
      currentIndex++;
    } else {
      clearInterval(typingInterval);
    }
  }, 100);

  // Cursor blinking animation (CSS handles most of it, this ensures initial state)
  const cursorInterval = setInterval(() => {
    cursorVisible = !cursorVisible;
    cursor.style.opacity = cursorVisible ? '1' : '0';
  }, 500);

  // Cleanup on page unload
  window.addEventListener('beforeunload', () => {
    clearInterval(typingInterval);
    clearInterval(cursorInterval);
  });
});
