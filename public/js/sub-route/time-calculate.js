function subtractTime(baseTime, subtract) {
    const [baseH, baseM] = baseTime.split(":").map(Number);
    const [subH, subM] = subtract.split(":").map(Number);

    const date = new Date();
    date.setHours(baseH);
    date.setMinutes(baseM);
    date.setSeconds(0);

    // ลบชั่วโมงและนาที
    date.setHours(date.getHours() - subH);
    date.setMinutes(date.getMinutes() - subM);

    // จัดรูปแบบผลลัพธ์เป็น HH:mm
    const hh = String(date.getHours()).padStart(2, '0');
    const mm = String(date.getMinutes()).padStart(2, '0');
    return `${hh}:${mm}`;
}

$(document).ready(function () {

    $('.time-mask').on('input', function () {
        //console.log(123);
        let formatted = formatTime($(this).val(), {
            timePattern: ['h', 'm']
        });
        $(this).val(formatted);
    });

    // เรียกใช้งาน registerCursorTracker แบบ jQuery
    registerCursorTracker({
        input: $('.time-mask')[0], // jQuery เป็น array-like object ต้องดึง DOM element ด้วย index 0
        delimiter: ':'
    });


    $('#_close_time, #depart_time').on('keyup', function () {
        const departTime = $('#depart_time').val(); // "07:30"
        const closeTime = $('#_close_time').val(); // เช่น "07" หรือ "07:3" หรือ "07:30"

        // ฟังก์ชันตรวจสอบเวลาเบื้องต้น ว่ามีรูปแบบ HH:mm ครบ
        function isValidTime(t) {
            if (!t.includes(':')) return false; // ไม่มี colon
            const parts = t.split(':');
            if (parts.length !== 2) return false; // ไม่ใช่ 2 ส่วน
            const [h, m] = parts;
            if (h === '' || m === '') return false; // ว่างชั่วโมงหรือวินาที
            if (isNaN(h) || isNaN(m)) return false; // ไม่ใช่ตัวเลข
            return true;
        }

        if (isValidTime(departTime) && isValidTime(closeTime)) {
            const result = subtractTime(departTime, closeTime);
            console.log('Cut-Off เวลา:', result);
            console.log('Cut-Off เวลา:', closeTime);
            $('#box-result-time').text('Cut-Off เวลา:' + result);
            $('#close_time').val(result);
        } else {
            console.log('noting');
            $('#box-result-time').text(''); // เคลียร์ผลลัพธ์ถ้าข้อมูลไม่สมบูรณ์
            $('#close_time').val('');
        }
    });

});
