from playwright.sync_api import sync_playwright
import time

def run():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context()
        page = context.new_page()

        # Verify PWA Login
        print("Checking PWA Login...")
        try:
            page.goto("http://localhost:8000/frontend/pwa/index.html")
            page.wait_for_selector("#login-form")
            page.screenshot(path="verification_pwa.png")
            print("PWA Login screenshot saved.")
        except Exception as e:
            print(f"Error checking PWA: {e}")

        # Verify Panel Login
        print("Checking Panel Login...")
        try:
            page.goto("http://localhost:8000/frontend/panel/index.html")
            page.wait_for_selector("#login-form")
            page.screenshot(path="verification_panel.png")
            print("Panel Login screenshot saved.")
        except Exception as e:
            print(f"Error checking Panel: {e}")

        browser.close()

if __name__ == "__main__":
    run()
