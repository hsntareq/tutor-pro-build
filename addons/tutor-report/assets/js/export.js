jQuery(document).ready(function($) {
    const div = document.getElementById('download_analytics');

    div.onclick = (e) => {
        const button    = e.target;
        button.classList.add('active');
        var buttonHtml  = button.innerHTML;
            
        $.ajax({
            url : window._tutorobject.ajaxurl,
            type : 'POST',
            data : {
                action: 'export_analytics'
            },
            beforeSend: function () {
                button.innerHTML = window.tutorDotLoader();
            },
            success: function (data) {
                if ( data.success ) {
                    downloadData(data.data)
                }

            },
            complete: function () {
                button.innerHTML = buttonHtml;
                button.classList.remove('active');
            }
        });
    }
    function downloadData(_tutor_export) {
        const student_data  = _tutor_export.students;
        const earning_data  = _tutor_export.earnings;
        const discount_data = _tutor_export.discounts.length;
        const refund_data   = _tutor_export.refunds;
        var zip = new JSZip();
        
        // get keys as array
        if ( student_data.length ) {
            const students_keys = Object.keys(student_data[0]);
            const student_string = [students_keys.join(",") , student_data.map(row => students_keys.map(key => row[key]).join(",")).join("\n")].join("\n");
            //generate csv
            const student_csv = new Blob([student_string])
            zip.file("students.csv", student_csv);
        }

        if ( earning_data.length ) {
            const earning_keys = Object.keys(earning_data[0]);
            const earning_string = [earning_keys.join(",") , earning_data.map(row => earning_keys.map(key => row[key]).join(",")).join("\n")].join("\n");
            //generate csv
            const earing_csv = new Blob([earning_string]);
            //add file
            zip.file("earnings.csv", earing_csv);
        }

        if ( discount_data.length ) {
            const discount_keys = Object.keys(discount_data[0]);
            const discount_string = [discount_keys.join(",") , discount_data.map(row => discount_keys.map(key => row[key]).join(",")).join("\n")].join("\n");
            //generate csv
            const discount_csv = new Blob([discount_string])
            //add file 
            zip.file("discounts.csv", discount_csv);
        }

        if ( refund_data.length ) {
            const refund_keys = Object.keys(refund_data[0]);
            const refund_string = [refund_keys.join(",") , refund_data.map(row => refund_keys.map(key => row[key]).join(",")).join("\n")].join("\n")
            //generate csv
            const refund_csv = new Blob([refund_string])
            //add file
            zip.file("refunds.csv", refund_csv);  
        }
        //generate zip archive
        try {
            zip.generateAsync({type:"blob"})
            .then(function(content) {
                const blob = new Blob([content], {type: 'application/zip'});
                const link = document.createElement('a');
                document.body.appendChild(link);
                link.download = "analytics-data.zip";
                link.href = URL.createObjectURL(blob);
                link.click();
                URL.revokeObjectURL(link.href);
            });
        } catch(err) {
            alert(err)
        }
    }
});