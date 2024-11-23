let count = 0;

const counterElement = document.getElementById('counter');
const popcatElement = document.getElementById('popcat');
const leaderboardElement = document.getElementById('leaderboard');

function handleClick() {
  count++;
  counterElement.textContent = count;

  // Tambahin animasi saat diclick kekanan atau kekiri secara random
  const randomAngle = Math.random() > 0.5 ? 15 : -15;
  counterElement.style.transform = `scale(1.5) rotate(${randomAngle}deg)`;

  setTimeout(() => {
    counterElement.style.transform = 'scale(1) rotate(0deg)';
  }, 300);

  // Update Scorenya
  updateScore();

  // Suara Rimuru ><
  playPopSound();
}

function playPopSound() {
  const audio = new Audio('rimuruketawa.mp3');
  audio.play();
}

function updateScore() {
  fetch('update_score.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ score: count }),
  })
    .then(() => fetchLeaderboard());
}

function fetchLeaderboard() {
  fetch('get_leaderboard.php')
    .then((response) => response.json())
    .then((data) => {
      leaderboardElement.innerHTML = '';
      let rank = 1;
      data.forEach((entry) => {
        const div = document.createElement('div');
        div.innerHTML = `<span>#${rank++} ${entry.country}</span><span>${entry.score}</span>`;
        leaderboardElement.appendChild(div);
      });
    });
}

document.addEventListener('click', handleClick);

function updateClock() {
    const clockElement = document.getElementById('clock');
    const now = new Date();
  
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
  
    clockElement.textContent = `${hours}:${minutes}:${seconds}`;
}
  
// Update jam setiap detiknya
setInterval(updateClock, 1000);
updateClock();
  
fetchLeaderboard();


