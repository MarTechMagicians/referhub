# Database Schema

### **1. Users Table:**

- **user_id:** Primary Key, unique identifier for users.
- **identification_method:** Value object storing the identification method (e.g., email, username, userId).
- **referral_code_id:** Foreign Key, link to the referral codes table.

### **2. Referral Codes Table:**

- **referral_code_id:** Primary Key, unique identifier for referral codes.
- **code_value:** The actual referral code value.
- **generation_strategy:** Information on how the code was generated (CLI, REST, Batch).
- **user_id:** Foreign Key, link to the users table.

### **3. Events Table:**

- **event_id:** Primary Key, unique identifier for events.
- **event_type:** Type of event (e.g., Sign Up, Purchase).
- **event_data:** JSON or serialized data containing event details.
- **referral_code_id:** Foreign Key, link to the referral codes table.

### **4. Webhooks Table:**

- **webhook_id:** Primary Key, unique identifier for webhooks.
- **webhook_url:** URL to call when the webhook is triggered.
- **notification_id:** Foreign Key, link to notifications table.

### **5. Notifications Table:**

- **notification_id:** Primary Key, unique identifier for notifications.
- **event_id:** Foreign Key, link to the events table.
- **webhook_id:** Foreign Key, link to the webhooks table.