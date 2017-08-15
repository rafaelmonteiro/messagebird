# MessageBird API Client

This implementation uses [MessageBird PHP REST API](https://github.com/messagebird/php-rest-api) to send SMS messages.

## Requirements
- PHP 7+
- Composer
- [Sign up](https://www.messagebird.com/en/signup) for a MessageBird account
- Create a new access_key in the [developers](https://www.messagebird.com/app/en/settings/developers/access) section

## Assignments
- When an incoming message content/body is longer than 160 chars, split it into multiple parts
- Empty or incorrect parameter values cannot be sent to MessageBird
- Make just one API request per second

## Installation

`composer install`

## Local configuration (using PHP internal server)

- Input your API access key in `Config/config.json` file

`php -S localhost:8080 -t web`

## API structure

| Route	| Method | Description |
| :--- | :----: | :--- |
| /api/message/sms | POST | Send a SMS message |
| /api/getBalance | GET | Show user's balance |

## API Data Example

| Route	| Content-Type | Body |
| :--- | :----: | :--- |
| /api/message/sms | application/json | {"recipient": [5511111111111,5511111111112],"originator":"MessageBird","message":"This is a test message."} |

## References

[MessageBird Developers API Docs](https://developers.messagebird.com/docs/messaging)
