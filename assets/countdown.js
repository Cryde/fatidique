import {intervalToDuration, parseISO, isPast, differenceInSeconds, differenceInHours, differenceInDays} from 'date-fns';

const clock = document.querySelector('#clock');
const countdown = document.querySelector('#countdown');
const celebration = document.querySelector('#celebration');
const funStats = document.querySelector('#fun-stats');

if (clock && countdown) {
  const yearsEl = document.querySelector('#years');
  const monthsEl = document.querySelector('#months');
  const daysEl = document.querySelector('#days');
  const hoursEl = document.querySelector('#hours');
  const minutesEl = document.querySelector('#minutes');
  const secondsEl = document.querySelector('#secondes');
  const totalHoursEl = document.querySelector('#total-hours');
  const totalSecondsEl = document.querySelector('#total-seconds');
  const sleepsEl = document.querySelector('#sleeps');
  const coffeesEl = document.querySelector('#coffees');
  const episodesEl = document.querySelector('#episodes');
  const pizzasEl = document.querySelector('#pizzas');
  const songsEl = document.querySelector('#songs');
  const walksEl = document.querySelector('#walks');
  const targetDate = parseISO(clock.dataset.datetime);
  const eventLabel = clock.dataset.label;
  const originalTitle = document.title;
  let intervalId = null;

  const setValue = (element, value) => {
    if (element) {
      element.style.setProperty('--value', value);
    }
  };

  const formatNumber = (num) => {
    return new Intl.NumberFormat('fr-FR').format(num);
  };

  const showCelebration = () => {
    countdown.classList.add('hidden');
    if (funStats) funStats.classList.add('hidden');
    celebration.classList.remove('hidden');
    document.title = `🎉 ${eventLabel}`;
    if (intervalId) {
      clearInterval(intervalId);
    }
  };

  const updateTitle = (duration) => {
    const parts = [];
    if (duration.years) parts.push(`${duration.years}a`);
    if (duration.months) parts.push(`${duration.months}m`);
    if (duration.days) parts.push(`${duration.days}j`);
    if (duration.hours) parts.push(`${duration.hours}h`);
    if (duration.minutes) parts.push(`${duration.minutes}min`);
    if (duration.seconds !== undefined) parts.push(`${duration.seconds}s`);

    const timeStr = parts.slice(0, 3).join(' '); // Show max 3 units
    document.title = `${timeStr} - ${eventLabel}`;
  };

  const updateCountdown = () => {
    const now = new Date();

    if (isPast(targetDate)) {
      showCelebration();
      return;
    }

    const duration = intervalToDuration({
      start: now,
      end: targetDate
    });

    setValue(yearsEl, duration.years || 0);
    setValue(monthsEl, duration.months || 0);
    setValue(daysEl, duration.days || 0);
    setValue(hoursEl, duration.hours || 0);
    setValue(minutesEl, duration.minutes || 0);
    setValue(secondsEl, duration.seconds || 0);

    // Update title
    updateTitle(duration);

    // Fun stats
    const totalHours = differenceInHours(targetDate, now);
    const totalMinutes = Math.floor(differenceInSeconds(targetDate, now) / 60);
    const totalDays = differenceInDays(targetDate, now);

    if (totalHoursEl) {
      totalHoursEl.textContent = formatNumber(totalHours);
    }
    if (totalSecondsEl) {
      totalSecondsEl.textContent = formatNumber(differenceInSeconds(targetDate, now));
    }
    if (sleepsEl) {
      sleepsEl.textContent = formatNumber(totalDays + 1);
    }
    if (coffeesEl) {
      // ~3 coffees per day
      coffeesEl.textContent = formatNumber(totalDays * 3);
    }
    if (episodesEl) {
      // ~45 min per episode
      episodesEl.textContent = formatNumber(Math.floor(totalMinutes / 45));
    }
    if (pizzasEl) {
      // ~1 pizza every 3 days
      pizzasEl.textContent = formatNumber(Math.floor(totalDays / 3));
    }
    if (songsEl) {
      // ~3.5 min per song
      songsEl.textContent = formatNumber(Math.floor(totalMinutes / 3.5));
    }
    if (walksEl) {
      // ~5 km/h walking speed
      walksEl.textContent = formatNumber(totalHours * 5);
    }
  };

  updateCountdown();
  intervalId = setInterval(updateCountdown, 1000);

  setTimeout(() => {
    if (!isPast(targetDate)) {
      countdown.classList.remove('invisible');
      if (funStats) funStats.classList.remove('invisible');
    }
  }, 100);
}