window.onload = () => {
   
    const search_icon = document.getElementById("tutor_analytics_search_icon");
    if ( search_icon ) {
        search_icon.onclick = () => {
            const search_form = document.getElementById("tutor_analytics_search_form");
            search_form.submit();
        }
    }
    /**
     * Tabs above graph 
     * 
     * @since 1.9.9
     */
    const tabs = document.querySelectorAll(".tab");
    const tab_contents = document.querySelectorAll(".tab-content");

    for ( let tab of tabs ) {
        tab.onclick = (event) => {
            const target = event.target;
            //remove active class from tab
            for ( let tab of tabs ) {
                if ( tab.classList.contains('active') ) {
                    tab.classList.remove('active')
                }
            }
            //add active class on tab button
            target.closest('.tab').classList.add('active');
            //update active tab content
            for ( let tab_content of tab_contents ) {
                if ( tab_content.classList.contains('active') ) {
                    tab_content.classList.remove('active')
                }
            }
            //active tab content
            document.querySelector(`#${target.closest('.tab').dataset.toggle}`).classList.add('active');
        }
    }

    /**
     * Course progress popup
     * 
     */
    
    /**
     * Prepare Line Charts for creating dynamically
     * 
     * It will create four graph as mentioned on charts array of obj
     * 
     * @since 1.9.9
     */
    for ( let chart of _tutor_analytics ) {
        let ctx = document.getElementById(`${chart.id}_canvas`).getContext('2d');

        const labels = [];
        const values = [];
        const fees   = [];   
        for( let [key, value] of Object.entries(chart.data)) {
            let options = {month: 'short', day: 'numeric' };
            let date  = new Date(value.date_format);
            let new_date = date.toLocaleDateString("en-US", options);

            labels.push(new_date);
            values.push(value.total);
            if ( value.fees ) {
                fees.push(value.fees);
            }
        }
        const datasets = [];
        datasets.push(                    {
            label: chart.label,
            backgroundColor: '#3057D5',
            borderColor: '#3057D5',
            data:  values,
            borderWidth: 2,
            fill: false,
            lineTension: 0,
        });
        if ( fees.length ) {
            datasets.push(                    {
                label: chart.label2,
                backgroundColor: 'rgba(200, 0, 0, 1)',
                borderColor: 'rgba(200, 0, 0, 1)',
                data:  fees,
                borderWidth: 2,
                fill: false,
                lineTension: 0,
            });
        }
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            min: 0, // it is for ignoring negative step.
                            beginAtZero: true,
                            callback: function(value, index, values) {
                                if (Math.floor(value) === value) {
                                    return value;
                                }
                            }
                        }
                    }]
                },
                legend: {
                    display: false
                }
            }
            
        });    
    }
 //modal
 (function tutorModal() {
	document.addEventListener('click', (e) => {
		const attr = 'data-tutor-modal-target';
		const closeAttr = 'data-tutor-modal-close';
		const overlay = 'tutor-modal-overlay';

		if (e.target.hasAttribute(attr) || e.target.closest(`[${attr}]`)) {
			e.preventDefault();
			const id = e.target.hasAttribute(attr)
				? e.target.getAttribute(attr)
				: e.target.closest(`[${attr}]`).getAttribute(attr);
			const modal = document.getElementById(id);
			if (modal) {
				//modal.classList.add('tutor-is-active');
			}
		}

		if (
			e.target.hasAttribute(closeAttr) ||
			e.target.classList.contains(overlay) ||
			e.target.closest(`[${closeAttr}]`)
		) {
			e.preventDefault();
			const modal = document.querySelectorAll('.tutor-modal.tutor-is-active');
			modal.forEach((m) => {
				m.classList.remove('tutor-is-active');
			});
		}
	});
})();

const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;
const comparer = (idx, asc) => (a, b) => ((v1, v2) =>
    v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
    )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

    document.querySelectorAll(".tutor-analytics-js-sorting").forEach(th => th.addEventListener('click', (() => {
        const table = th.closest('table');
        const tbody = table.querySelector('tbody');
        const icon   = th.getAttribute('data-icon');
        const order  = th.getAttribute('data-order');
        const targetImg = th.getAttribute('data-id');
        if ( order == 'up') {
            th.setAttribute('data-order', 'down')
        }
        if ( order == 'down') {
            th.setAttribute('data-order', 'up')
        }
        let showIcon = order == 'up' ? 'down' : 'up';
        document.getElementById(targetImg).src = `${icon}${showIcon}.svg`;
        Array.from(tbody.querySelectorAll('tr'))
            .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
            .forEach(tr => tbody.appendChild(tr) );
        })
    ));
}

function tutorModal(e) {
    console.log('ddd')
    const attr = 'data-tutor-modal-target';
    const closeAttr = 'data-tutor-modal-close';
    const overlay = 'tutor-modal-overlay';

    if (e.target.hasAttribute(attr) || e.target.closest(`[${attr}]`)) {
        e.preventDefault();
        const id = e.target.hasAttribute(attr)
            ? e.target.getAttribute(attr)
            : e.target.closest(`[${attr}]`).getAttribute(attr);
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('tutor-is-active');
        }
    }

    if (
        e.target.hasAttribute(closeAttr) ||
        e.target.classList.contains(overlay) ||
        e.target.closest(`[${closeAttr}]`)
    ) {
        e.preventDefault();
        const modal = document.querySelectorAll('.tutor-modal.tutor-is-active');
        modal.forEach((m) => {
            m.classList.remove('tutor-is-active');
        });
    }

};

jQuery(document).ready(function($) {

    function viewProgress(e) {
        e.preventDefault();
        $.ajax({
            url : window._tutorobject.ajaxurl,
            type : 'POST',
            data : {
                course_id: e.target.dataset.course_id,
                total_progress: e.target.dataset.total_progress,
                total_lesson: e.target.dataset.total_lesson,
                completed_lesson: e.target.dataset.completed_lesson,
                total_assignment: e.target.dataset.total_assignment,
                completed_assignment: e.target.dataset.completed_assignment,
                total_quiz: e.target.dataset.total_quiz,
                completed_quiz: e.target.dataset.completed_quiz,
                action: 'view_progress'
            },
            beforeSend: function () {
                
            },
            success: function (data) {
                document.getElementById('tutor_progress_modal_content').innerHTML = data;
                const div = document.getElementById('modal-sticky-1');
                div.classList.add('tutor-is-active');
            },
            complete: function () {
                
            }
        });
        
    }
    // const progress_button = $("#analytics_view_course_progress");
    // if ( progress_button ) {
        
    // }
    $(".analytics_view_course_progress").on( 'click', function(e) {
        viewProgress(e);
    })
})