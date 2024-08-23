import sys
import json
import html
import asyncio
from baml_client import b
from baml_client.types import Resume

async def process_transcript(transcript):
    # Decode HTML entities
    transcript = html.unescape(transcript)

    # Example resume text
    resume_text = transcript

    # Extract resume using baml_client
    resume = b.ExtractResume(resume_text)

    # Fully type-checked and validated!
    assert isinstance(resume, Resume)

   # Convert the resume object to a JSON string
    resume_json = json.dumps(resume.__dict__)

    return resume_json

try:
    # Check if the script received the argument
    if len(sys.argv) > 1:
        # Read the file path from the command line argument
        file_path = sys.argv[1]

        # Read the encoded transcript from the file
        with open(file_path, 'r') as file:
            encoded_transcript = file.read()

        # Decode the JSON-encoded string
        transcript = json.loads(encoded_transcript)

        # Run the async function to process the transcript
        resume_json = asyncio.run(process_transcript(transcript))
        print(resume_json)
    else:
        print("No input received.")
except Exception as e:
    print(f"Error: {e}")