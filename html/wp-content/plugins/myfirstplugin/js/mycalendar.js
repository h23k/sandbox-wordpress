const myCalendar = (() => {
  let holidays = {};

  return {
    sayHello: () => {
      console.log('hello, myCalendar');
    },
    setHoliday: (days) => {
      holidays = days;
    },
    isHoliday: (year, month, day) => {
      const months = holidays[year] || {};
      const days = months[month] || {};
      return days[day];
    }
  };
})();

document.addEventListener('DOMContentLoaded', function() {
  const container = document.getElementById('calendar');
  if (container === null) {
    console.log('Not found: calendar id');
    return;
  }

  // 今日
  const date = new Date();
  const year = date.getFullYear();
  const month = date.getMonth() + 1;
  const day = date.getDate();
  // 前月末日
  const lastDay = new Date((month !== 1) ? year : year - 1, (month !== 1) ? month - 1 : 12, 0).getDate();
  // 今月
  const firstDay = 1 - new Date(year, month - 1, 1).getDay();
  const endDay = new Date(year, month, 0).getDate();
  // 曜日
  const weeks = [
    ['sun', '日'],
    ['mon', '月'],
    ['tue', '火'],
    ['wed', '水'],
    ['thu', '木'],
    ['fri', '金'],
    ['sat', '土']
  ];

  let calendarHtml = '';
  // 年月
  calendarHtml += `<div class="ym"><span class="year">${year}</span>年<span class="month">${month}</span>月</div>`;
  // 曜日
  calendarHtml += '<div class="weeks">';
  weeks.forEach((item) => {
    calendarHtml += `<div class="week ${item[0]}"><span>${item[1]}</span></div>`;
  });
  calendarHtml += '</div>';
  // 日付
  calendarHtml += '<div class="days">';
  for (var num = firstDay, i = 0; num <= endDay || i % 7 !== 0; num++, i++) {
    let d = num;
    let m = month;
    let y = year;
    if (num < 1) {
      // 前月
      d = lastDay + num;
      m = (month !== 1) ? month - 1 : 12;
      y = (m === 12) ? year - 1 : year;
    } else if (endDay < num) {
      // 来月
      d = num - endDay;
      m = (month !== 12) ? month + 1 : 1;
      y = (m === 1) ? year + 1 : year;
    }
    const holiday = myCalendar.isHoliday(y, m, d) || '';

    let classes = 'day ';
    classes += weeks[i % 7][0];
    classes += (num === day) ? ' today' : '';
    classes += (num < 1) ? ' last-month' : '';
    classes += (endDay < num) ? ' next-month' : '';
    classes += (holiday !== '') ? ' holiday' : '';
    calendarHtml += `<div class="${classes}"><span>${d}</span><b>${holiday}</b></div>`;
  }
  calendarHtml += '</div>';

  container.innerHTML = calendarHtml;
});
