Element.prototype.css = function (style) {
   for (prop in style) {
      if (style.hasOwnProperty(prop))
         this.style.setProperty(prop, style[prop]);
   }
}
Element.prototype.hasClass = function (className) {
   return this.classList.contains(className) || this.parentNode.classList.contains(className);
}

/** hidden menu */
document.addEventListener('click', function(e) {
	let clicked = e.target.closest('.btn-dropdown');
	if (clicked) {
		let menu = document.getElementsByClassName('btn-dropdown-menu'),
			 thisMenu = clicked.getElementsByClassName('btn-dropdown-menu')[0];
		for (let i = menu.length - 1; i >= 0; i--) {
			if (menu[i] === thisMenu) thisMenu.classList.toggle('show');
			else menu[i].classList.remove('show');
		}
	}
	else
		!!document.querySelector('.btn-dropdown-menu.show') && document.querySelector('.btn-dropdown-menu.show').classList.remove('show');
});

// burger button
document.getElementById('burger').addEventListener('click', function() {
	let sidebar = document.getElementsByClassName('sidebar')[0],
	container = document.getElementsByClassName('container')[0];

	if (sidebar.getBoundingClientRect().left === 0) {
		sidebar.style.setProperty('left', '-100%');
		container.style.setProperty('margin-left', 0);
	} else {
		sidebar.style.setProperty('left', 0);
		container.style.setProperty('margin-left', '20%');
	}
});

// activate menu
let a = document.querySelectorAll('.menu-item a');
for (let i = a.length - 1; i >= 0; i--) {
	if (location.pathname.indexOf('/quan-tri') >= 0) { a[0].className = 'active'; break; }
	if (location.href == a[i].href) a[i].className = 'active';
	else a[i].className = '';
}

const Chart = function(o) {
	
	// define canvas size
	this.canvas = document.getElementById(o.canvasId);
	this.canvas.width = this.canvas.parentNode.getBoundingClientRect().width;
	this.canvas.height = 390;
	// define some constants
	this.name = o.name || '';
	this.lineLevel = o.hasOwnProperty('lineLevel') ? o.lineLevel : true;
	this.numLevel = o.numLevel || 5;
	this.xAxisLabel = o.xAxisLabel;
	this.yAxisLabel = o.yAxisLabel;
	this.paddingLeft = 30;
	this.paddingRight = 200;
	this.paddingTop = 10;
	this.paddingBottom = 60;
	this.paddingX = (this.canvas.width - this.paddingLeft - this.paddingRight) / (this.xAxisLabel.length - 1);
	this.paddingY = (this.canvas.height - this.paddingTop - this.paddingBottom) / (this.yAxisLabel.length - 1);

	this.ctx = this.canvas.getContext('2d');
	this.charts = o.charts;
};

/**
 * draw chart
 * @method draw
 */
Chart.prototype.draw = function() {
	let x, chart, data, len;

	this.drawXAxis();
	this.drawYAxis();
	this.lineLevel && this.drawLineLevel();
	this.name != '' && this.setName();
	
	for (let i = 0; i < this.charts.length; i++) {
		chart = this.charts[i];
		data = this.offset(chart.data);
		x = this.paddingLeft;

		for (let j = 0, len = data.length; j < len; j++) {
			// create point at each data[j]
			this.ctx.beginPath();
			this.ctx.fillStyle = '#f00';
			this.ctx.arc(x, data[j], 2, 0, Math.PI * 2);
			this.ctx.fill();
			if (j == len - 1) break;
			
			x += this.paddingX;
			this.ctx.lineTo(x, data[j+1]);
			this.ctx.strokeStyle = chart.color || '#000';
			this.ctx.stroke();
		}
	}
	this.setAnotation();
};

Chart.prototype.setAnotation = function() {
	let x = this.canvas.width - this.paddingRight + 20, y = this.paddingTop;
	
	this.ctx.beginPath();
	for (let i = 0; i < this.charts.length; i++) {
		this.ctx.fillStyle = this.charts[i].color;
		this.ctx.fillRect(x, y, 10, 10);
		this.ctx.fillStyle = '#000';
		this.ctx.font = '400 1.2rem "Source San Pro", sans-serif';
		this.ctx.textBaseline = 'middle';
		this.ctx.fillText(this.charts[i].label, x + 50, y + 7);
		y += 30;
	}
};

/**
 * since canvas coordinate axis from top and left
 * we have to re-calc offset to draw from bottom
 * @method offset
 * @param  {array} data
 * @return {array}
 */
Chart.prototype.offset = function(data) {
	let height = this.canvas.height - this.paddingBottom;
	for (let i = 0; i < data.length; i++)
		data[i] = Math.ceil(height * (1 - data[i] / 100)) || this.paddingTop;
	return data;
};

Chart.prototype.setName = function() {
	this.ctx.fillStyle = '#000';
	this.ctx.font = '700 1.5em "Source Sans Pro", sans-serif';
	this.ctx.textAlign = 'center';
	this.ctx.fillText(this.name, this.canvas.width / 2, this.canvas.height - 20);
};

/**
 * draw baseline for some main levels
 * @method drawLineLevel
 */
Chart.prototype.drawLineLevel = function() {
	// calculate padding between each level
	let padding = (this.canvas.height - this.paddingBottom - this.paddingTop) / (this.numLevel - 1),
		 right = this.canvas.width - this.paddingRight, y = this.paddingTop;

	this.ctx.beginPath();
	for (let i = 0; i < this.numLevel; i++) {
		this.ctx.moveTo(this.paddingLeft, y);
		this.ctx.lineTo(right, y);
		this.ctx.strokeStyle = '#999';
		this.ctx.stroke();
		y += padding;
	}
};

Chart.prototype.drawXAxis = function() {
	// draw axis
	this.ctx.beginPath();
	this.ctx.strokeStyle = '#999';
	this.ctx.font = '1em "Source Sans Pro", sans-serif';
	this.ctx.textAlign = 'center';
	this.ctx.save();
	this.ctx.moveTo(this.paddingLeft, this.canvas.height - this.paddingBottom);
	this.ctx.lineTo(this.canvas.width - this.paddingRight, this.canvas.height - this.paddingBottom);
	this.ctx.stroke();
	// draw ticks and labels
	let x = this.paddingLeft;
	for (let i = 0; i < this.xAxisLabel.length; i++) {
		this.ctx.beginPath();
		this.ctx.moveTo(x, this.canvas.height - this.paddingBottom);
		this.ctx.lineTo(x, this.canvas.height - 50);
		this.ctx.fillText(this.xAxisLabel[i], x, this.canvas.height - 40);
		this.ctx.stroke();
		x += this.paddingX;
	}
};

Chart.prototype.drawYAxis = function() {
	// draw axis
	this.ctx.beginPath();
	this.ctx.restore();
	this.ctx.moveTo(this.paddingLeft, this.canvas.height - this.paddingBottom);
	this.ctx.lineTo(this.paddingLeft, this.paddingTop);
	this.ctx.stroke();
	// draw ticks and labels
	let y = this.canvas.height - this.paddingBottom;
	for (let i = 0; i < this.yAxisLabel.length; i++) {
		this.ctx.beginPath();
		this.ctx.moveTo(this.paddingLeft, y);
		this.ctx.lineTo(this.paddingLeft - 10, y);
		this.ctx.textBaseline = 'middle';
		this.ctx.fillText(this.yAxisLabel[i], this.paddingLeft - 20, y);
		this.ctx.stroke();
		y -= this.paddingY;
	}
};

// origin
let boards = document.getElementsByClassName('board');
for (let i = boards.length - 1; i >= 0; i--) {
	boards[i].addEventListener('click', function() {
		let self = this;
		$.ajax({
			url: '/Statistic/getMetrics/' + (i+1),
			success: function(res) {
				res = JSON.parse(res);
				drawChart(self.getElementsByClassName('board-title')[0].innerHTML, res);			
			},
			error: function(err) {
				console.error(err);
			}
		});
	});
}

window.addEventListener('load', function() {
	$.ajax({
		url: '/Statistic/getMetrics/0',
		success: function(res) {
			res = JSON.parse(res);
			drawChart('Tổng quan', res);			
		},
		error: function(err) {
			console.error(err);
		}
	});
});

function drawChart(name, metrics) {
	let days = [], charts = [];
	
	for (let i = 0; i < 30; i++) {days[i] = i + 1; }
	for (let i = metrics.length - 1; i >= 0; i--) {
		charts[i] = {
			data: metrics[i].data,
			color: '#'+((1<<24)*Math.random()|0).toString(16),
			label: metrics[i].label
		};
	}

	let chart = new Chart({
		canvasId: 'chart',
		name: name,
		xAxisLabel: days,
		yAxisLabel: [0, 25, 50, 75, 100],
		charts: charts
	});
	chart.draw();	
}