	var timerId = null
	var obj = new Array()

	function prepareShowAlt(event){
		idd = this.id
		if (idd.search("title_") == 0){
			idd = idd.substring(6)
			if ((typeof titles != "undefined") && (typeof titles[idd] != "undefined") && titles[idd].length > 0){
				text = titles[idd]
			}else{
				return;
			}
		}else{
			return
		}

		x = 0;
		y = 0;
		oX = 0;
		if (document.all){
			x = window.event.clientX
			y = window.event.clientY + document.body.scrollTop
		}else{
			x = event.clientX - oX
			y = event.clientY + window.scrollY
		}
		x += Math.round(xs[idd])
		y += Math.round(ys[idd])

		obj = new Array()
		obj[0] = text
		obj[1] = x
		obj[2] = y

		timerId = setTimeout("showAlt()", 500);
	}

	function showAlt(){
		text = obj[0]
		x = obj[1]
		y = obj[2]
		altd = document.getElementById('altDiv')
		altd.innerHTML = "<table cellpadding=3 cellspacing=0 style='background-color: #EEF6FF; border: solid #6A91B4 1px; font-family: tahoma;" + (text.length > 30 ? "width: 200px;" : "") +"'><tr><td style='color: #414041; font-size: 11px;'>" + text + "</td></tr></table>"
		altd.style.left = Math.min(x + 10,document.body.clientWidth-201)
		altd.style.top = y
		altd.style.display = 'inline'
	}

	function hideAlt(){
		obj = new Array()
		clearTimeout(timerId)
		altd = document.getElementById('altDiv')
		altd.innerHTML = ""
		altd.style.left = -100
		altd.style.top = -100
		altd.style.display = 'none'
	}

	function initAlts(){
		arr = document.getElementsByName('title')
		titles = new Array()
		xs = new Array()
		ys = new Array()
		for (i=0; i<arr.length; i++){
			itemm = arr.item(i)
			itemm.onmouseover = prepareShowAlt
			itemm.onmouseout = hideAlt
			itemm.id = "title_" + i
			titles[i] = itemm.title
			xs[i] = (typeof itemm.xs == 'undefined' ? 0 : itemm.xs)
			ys[i] = (typeof itemm.ys == 'undefined' ? 0 : itemm.ys)
			itemm.title = ""
		}
	}