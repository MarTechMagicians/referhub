# API Specifications

### **1. Referral Code Generation:**

- ************************Explanation:************************

  This method will be called when a user wants to become a referrer.

  The user will share his identification and then will get a unique referral code to share.

- **Endpoint:** **`/api/referral-codes`**
- **Method:** **`POST`**
- **Request Parameters:**

```json
{
  "identificationMethod": "email",
  "identificationValue": "john.doe@test.com"
}
```

- **Response:**
    - **Status Code:** **`201 Created`**
    - **Body:**

```json
{
  "referralCode": "ABC123",
	"userId": "123"
}
```

- **Authentication:** Required.

### **2. Event Tracking:**

- ************************Explanation:************************

  This method will be called when a referred user has an action like sign up or purchase. The system which is responsible for the main action will call this endpoint to make sure that the referral has been succeeded.

  In the future, having user based authentication for this endpoint could be improved.

- **Endpoint:** **`/api/referrals`**
- **Method:** **`POST`**
- **Request Parameters:**

```json
{
  "eventType": "Sign Up",
  "referralCode": "ABC123",
  "userIdentification": {
		"identificationMethod": "email",
	  "identificationValue": "referreduser@test.com",
  },
  "eventData": {
    "additional": "data_here"
  }
}
```

- **Response:**
    - **Status Code:** **`201 Created`**
    - **Body:**

```json
{
  "eventType": "Sign Up",
  "referralCode": "123",
  "eventData": {
    "additional": "data_here"
  }
}
```

- **Authentication:** Required.

### **3. Webhook Management:**

- **Endpoint:** **`/api/webhooks`**
- **Methods:**
    - **POST:** Create a new webhook.
    - **Request Parameters (POST):**

```json
{
  "referralCode": "123",
  "url": "https://example.com/webhook",
  "method": "POST",
  "eventTypes": ["Sign Up", "Purchase"]
}

```

- **Response (POST):**
    - **Status Code:** **`201 Created`**
    - **Body:**

```json
{
  "referralCode": "123",
  "url": "https://example.com/webhook",
  "method": "POST",
  "eventTypes": ["Sign Up", "Purchase"]
}

```

- **GET:** Retrieve existing webhooks.
- **Response (GET):**
    - **Status Code:** **`200 OK`**
    - **Body:**

```json
[
  {
    "referralCode": "123",
    "webhookURL": "https://example.com/webhook",
		"webhookMethod": "POST",
    "eventTypes": ["Sign Up", "Purchase"]
  }
]

```

- **PUT:** Update a webhook.
- **DELETE:** Remove a webhook.
- **Authentication:** Required.

### **4. User Management (if needed):**

- **Endpoints and Methods:** Define as needed for user creation, updates, retrieval, etc.
- **Request Parameters:** Define as needed.
- **Example Response:**
    - **Status Code:** **`200 OK`**
    - **Body:**

```json
{
  "user_id": "456",
  "identification_method": "email",
  "email": "user@example.com",
  "referral_code_id": "123"
}

```

- **Authentication:** Required.

### **5. Error Handling:**

- **Standardized Error Codes and Messages:**
- **Example Error Response:**
    - **Status Code:** **`400 Bad Request`**
    - **Body:**

```json
{
  "error_code": "INVALID_INPUT",
  "message": "The provided input is not valid."
}

```