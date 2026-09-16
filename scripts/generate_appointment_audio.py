import asyncio
from pathlib import Path

import edge_tts


OUTPUT = Path('public/audio/elderly-assistant/appointment')
VOICE = 'ar-SA-HamedNeural'

WEEKDAYS = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت']
MONTHS = [
    'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
    'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر',
]
DAYS = [
    'الأول', 'الثاني', 'الثالث', 'الرابع', 'الخامس', 'السادس', 'السابع',
    'الثامن', 'التاسع', 'العاشر', 'الحادي عشر', 'الثاني عشر', 'الثالث عشر',
    'الرابع عشر', 'الخامس عشر', 'السادس عشر', 'السابع عشر', 'الثامن عشر',
    'التاسع عشر', 'العشرون', 'الحادي والعشرون', 'الثاني والعشرون',
    'الثالث والعشرون', 'الرابع والعشرون', 'الخامس والعشرون',
    'السادس والعشرون', 'السابع والعشرون', 'الثامن والعشرون',
    'التاسع والعشرون', 'الثلاثون', 'الحادي والثلاثون',
]
HOURS = [
    'الواحدة', 'الثانية', 'الثالثة', 'الرابعة', 'الخامسة', 'السادسة',
    'السابعة', 'الثامنة', 'التاسعة', 'العاشرة', 'الحادية عشرة', 'الثانية عشرة',
]


def clips():
    values = {
        'confirm-start.mp3': 'فهمت عليك. الموعد يوم',
        'at-hour.mp3': 'الساعة',
        'confirm-end.mp3': 'إذا الموعد صحيح، اضغط تأكيد الموعد. وإذا بدك تغيّره، اضغط إعادة المحاولة.',
        'prompt.mp3': 'احكي اليوم والساعة، مثل: بكرا الساعة ثلاثة العصر.',
        'error.mp3': 'ما قدرت أحدد الموعد. احكي اليوم والساعة بوضوح، مثل: بكرا الساعة ثلاثة العصر.',
        'confirmed.mp3': 'تم تأكيد الموعد.',
        'period-morning.mp3': 'صباحًا',
        'period-noon.mp3': 'ظهرًا',
        'period-afternoon.mp3': 'عصرًا',
        'period-evening.mp3': 'مساءً',
    }
    values.update({f'weekday-{index}.mp3': text for index, text in enumerate(WEEKDAYS)})
    values.update({f'day-{index}.mp3': text for index, text in enumerate(DAYS, start=1)})
    values.update({f'month-{index}.mp3': text for index, text in enumerate(MONTHS, start=1)})
    values.update({f'hour-{index}.mp3': text for index, text in enumerate(HOURS, start=1)})
    values.update({f'minute-{minute}.mp3': f'و {minute} دقيقة' for minute in range(1, 60)})
    return values


async def main():
    OUTPUT.mkdir(parents=True, exist_ok=True)
    semaphore = asyncio.Semaphore(5)

    async def generate(filename, text):
        async with semaphore:
            communicator = edge_tts.Communicate(text, VOICE, rate='-8%', volume='+15%')
            await communicator.save(str(OUTPUT / filename))

    await asyncio.gather(*(generate(filename, text) for filename, text in clips().items()))


if __name__ == '__main__':
    asyncio.run(main())
