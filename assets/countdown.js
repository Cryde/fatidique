import {intervalToDuration, parseISO, isPast, differenceInSeconds, differenceInHours, differenceInDays} from 'date-fns';

const clock = document.querySelector('#clock');
const countdown = document.querySelector('#countdown');
const countup = document.querySelector('#countup');
const celebration = document.querySelector('#celebration');
const funStats = document.querySelector('#fun-stats');
const funStatsPast = document.querySelector('#fun-stats-past');
const milestones = document.querySelector('#milestones');
const badge100 = document.querySelector('#badge-100');
const badge30 = document.querySelector('#badge-30');
const badge7 = document.querySelector('#badge-7');
const badge1 = document.querySelector('#badge-1');

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

  // Count up elements
  const upYearsEl = document.querySelector('#up-years');
  const upMonthsEl = document.querySelector('#up-months');
  const upDaysEl = document.querySelector('#up-days');
  const upHoursEl = document.querySelector('#up-hours');
  const upMinutesEl = document.querySelector('#up-minutes');
  const upSecondsEl = document.querySelector('#up-secondes');
  const pastHoursEl = document.querySelector('#past-hours');
  const pastSecondsEl = document.querySelector('#past-seconds');
  const pastSleepsEl = document.querySelector('#past-sleeps');
  const pastCoffeesEl = document.querySelector('#past-coffees');
  const pastEpisodesEl = document.querySelector('#past-episodes');
  const pastPizzasEl = document.querySelector('#past-pizzas');
  const pastSongsEl = document.querySelector('#past-songs');
  const pastWalksEl = document.querySelector('#past-walks');

  const targetDate = parseISO(clock.dataset.datetime);
  const eventLabel = clock.dataset.label;
  const originalTitle = document.title;
  let intervalId = null;
  let isCountingUp = false;

  const setValue = (element, value) => {
    if (element) {
      element.style.setProperty('--value', value);
    }
  };

  const formatNumber = (num) => {
    return new Intl.NumberFormat('fr-FR').format(Math.abs(num));
  };

  const showCountUp = () => {
    if (isCountingUp) return;
    isCountingUp = true;
    countdown.classList.add('hidden');
    if (funStats) funStats.classList.add('hidden');
    if (celebration) celebration.classList.add('hidden');
    if (milestones) milestones.classList.add('hidden');
    if (countup) countup.classList.remove('hidden', 'invisible');
    if (funStatsPast) funStatsPast.classList.remove('hidden', 'invisible');
  };

  const updateMilestones = (totalDays) => {
    if (!milestones) return;

    let hasMilestone = false;

    // Show badge if we're at or below the milestone threshold
    if (badge100 && totalDays <= 100 && totalDays > 30) {
      badge100.classList.remove('hidden');
      hasMilestone = true;
    }
    if (badge30 && totalDays <= 30 && totalDays > 7) {
      badge30.classList.remove('hidden');
      hasMilestone = true;
    }
    if (badge7 && totalDays <= 7 && totalDays > 1) {
      badge7.classList.remove('hidden');
      hasMilestone = true;
    }
    if (badge1 && totalDays <= 1 && totalDays >= 0) {
      badge1.classList.remove('hidden');
      hasMilestone = true;
    }

    if (hasMilestone) {
      milestones.classList.remove('hidden');
    }
  };

  const updateTitle = (duration, isPastEvent) => {
    const parts = [];
    if (duration.years) parts.push(`${duration.years}a`);
    if (duration.months) parts.push(`${duration.months}m`);
    if (duration.days) parts.push(`${duration.days}j`);
    if (duration.hours) parts.push(`${duration.hours}h`);
    if (duration.minutes) parts.push(`${duration.minutes}min`);
    if (duration.seconds !== undefined) parts.push(`${duration.seconds}s`);

    const timeStr = parts.slice(0, 3).join(' ');
    document.title = isPastEvent ? `+${timeStr} - ${eventLabel}` : `${timeStr} - ${eventLabel}`;
  };

  const updateCountUp = () => {
    const now = new Date();
    showCountUp();

    const duration = intervalToDuration({
      start: targetDate,
      end: now
    });

    setValue(upYearsEl, duration.years || 0);
    setValue(upMonthsEl, duration.months || 0);
    setValue(upDaysEl, duration.days || 0);
    setValue(upHoursEl, duration.hours || 0);
    setValue(upMinutesEl, duration.minutes || 0);
    setValue(upSecondsEl, duration.seconds || 0);

    updateTitle(duration, true);

    // Past fun stats
    const totalHours = differenceInHours(now, targetDate);
    const totalMinutes = Math.floor(differenceInSeconds(now, targetDate) / 60);
    const totalDays = differenceInDays(now, targetDate);

    if (pastHoursEl) pastHoursEl.textContent = formatNumber(totalHours);
    if (pastSecondsEl) pastSecondsEl.textContent = formatNumber(differenceInSeconds(now, targetDate));
    if (pastSleepsEl) pastSleepsEl.textContent = formatNumber(totalDays + 1);
    if (pastCoffeesEl) pastCoffeesEl.textContent = formatNumber(totalDays * 3);
    if (pastEpisodesEl) pastEpisodesEl.textContent = formatNumber(Math.floor(totalMinutes / 45));
    if (pastPizzasEl) pastPizzasEl.textContent = formatNumber(Math.floor(totalDays / 3));
    if (pastSongsEl) pastSongsEl.textContent = formatNumber(Math.floor(totalMinutes / 3.5));
    if (pastWalksEl) pastWalksEl.textContent = formatNumber(totalHours * 5);
  };

  const updateCountdown = () => {
    const now = new Date();

    if (isPast(targetDate)) {
      updateCountUp();
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

    updateTitle(duration, false);

    // Fun stats
    const totalHours = differenceInHours(targetDate, now);
    const totalMinutes = Math.floor(differenceInSeconds(targetDate, now) / 60);
    const totalDays = differenceInDays(targetDate, now);

    // Update milestone badges
    updateMilestones(totalDays);

    if (totalHoursEl) totalHoursEl.textContent = formatNumber(totalHours);
    if (totalSecondsEl) totalSecondsEl.textContent = formatNumber(differenceInSeconds(targetDate, now));
    if (sleepsEl) sleepsEl.textContent = formatNumber(totalDays + 1);
    if (coffeesEl) coffeesEl.textContent = formatNumber(totalDays * 3);
    if (episodesEl) episodesEl.textContent = formatNumber(Math.floor(totalMinutes / 45));
    if (pizzasEl) pizzasEl.textContent = formatNumber(Math.floor(totalDays / 3));
    if (songsEl) songsEl.textContent = formatNumber(Math.floor(totalMinutes / 3.5));
    if (walksEl) walksEl.textContent = formatNumber(totalHours * 5);
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