import asyncio
from pathlib import Path

import edge_tts


OUTPUT = Path('public/audio/elderly-assistant/unknown-intent.mp3')
TEXT = 'بقدر أساعدك بطلب خدمة، متابعة طلباتك، أو قراءة الإشعارات. جرّب تحكي: بدي أطلب دواء.'
VOICE = 'ar-SA-HamedNeural'


async def main():
    OUTPUT.parent.mkdir(parents=True, exist_ok=True)
    communicator = edge_tts.Communicate(TEXT, VOICE, rate='-8%', volume='+15%')
    await communicator.save(str(OUTPUT))


if __name__ == '__main__':
    asyncio.run(main())
