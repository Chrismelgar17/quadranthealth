import sys
import json
import html
import asyncio
from baml_client import b
from baml_client.types import Resume

async def process_transcript(transcript):
    # Decode HTML entities
    transcript = html.unescape(transcript)

    # Process the transcript (example: just print it)
    print(transcript)

    # Example resume text
    resume_text = transcript

    # Extract resume using baml_client
    resume = await b.ExtractResume(resume_text)

    # Fully type-checked and validated!
    assert isinstance(resume, Resume)

    # Print the extracted resume for demonstration
    print(resume)

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
        asyncio.run(process_transcript(transcript))
    else:
        print("No input received.")
except Exception as e:
    print(f"Error: {e}")