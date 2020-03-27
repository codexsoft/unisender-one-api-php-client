<?php

namespace CodexSoft\UnisenderOneApiSdk;

class Client
{
    private \GuzzleHttp\Client $guzzleClient;
    private string $username;
    private string $apiKey;

    public function __construct(string $username, string $apiKey)
    {
        $this->username = $username;
        $this->apiKey = $apiKey;
        $this->guzzleClient = new \GuzzleHttp\Client([
            'base_uri' => 'https://one.unisender.com'
        ]);
    }

    protected function sendPost(string $uri, array $data = []): array
    {
        $defaultParams = [
            'username' => $this->username,
            'api_key' => $this->apiKey,
        ];

        $data = \array_merge($defaultParams, $data);

        $response = $this->guzzleClient->request('POST', '/ru/transactional/api/v1'.$uri, [
            //'json' => $data,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => \json_encode($data, JSON_THROW_ON_ERROR, 512),
        ]);

        // todo: check that status is success
        $responseData = \json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        return $responseData;
    }

    /**
     * {
     *   "api_key": "MHYHJRaPGESH2DT34kU43HvyAikcS6SHFOzHT",
     *   "username": "ID9999999"
     * }
     *
     * {
     *   "status": "success",
     *   "balance": 1234.42,
     *   "currency": "USD"
     * }
     *
     * @see https://one.unisender.com/ru/docs/page/Balance
     * @return array
     */
    public function getBalance(): array
    {
        return $this->sendPost('/balance.json');
    }

    /**
     * {
     *   "api_key": "MHYHJRaPGESH2DT34kU43HvyAikcS6SHFOzHT",
     *   "username": "ID1234567",
     *   "message":
     *   {
     *     "template_engine" : "simple",
     *     "template_id" : "template_id",
     *     "global_substitutions":
     *     {
     *       "someVar":"some val"
     *     },
     *     "body":
     *     {
     *       "html": "<b>Hello {{substitutionName}}</b>",
     *       "plaintext": "Some plain text"
     *     },
     *     "subject": "Example subject",
     *     "from_email": "noreply@example.com",
     *     "from_name": "Some Name",
     *     "reply_to": "mail@example.com",
     *     "track_links" : 1,
     *     "track_read"  : 1,
     *     "recipients": [
     *       {
     *         "email": "recipient@example.com",
     *         "substitutions":
     *         {
     *           "substitutionName": "substitutionVal",
     *           "to_name": "Name Surname"
     *         },
     *         "metadata":
     *         {
     *            "key1" : "val1"
     *         }
     *       },
     *       {
     *         "email": "bad_email@com",
     *         "substitutions":
     *         {
     *           "substitutionName": "substitutionVal",
     *           "UNSUB_hash": "Qwcd1789"
     *         }
     *       }
     *     ],
     *     "metadata":
     *     {
     *       "key1" : "val1"
     *     },
     *     "headers":
     *     {
     *       "X-ReplyTo": "reply@example.com"
     *     },
     *     "attachments": [
     *       {
     *         "type": "text/plain",
     *         "name": "myfile.txt",
     *         "content": "ZXhhbXBsZSBmaWxl" //файл в base64, для использования в HTML должен быть передан как <img src="cid:IMAGECID">
     *       }
     *     ],
     *     "inline_attachments": [
     *       {
     *         "type": "image/png",
     *         "name": "IMAGECID",
     *         "content": "iVBORw0KGgo" //файл в base64, для использования в HTML должен быть передан как <img src="cid:IMAGECID">
     *       }
     *     ],
     *     "options":
     *     {
     *       "unsubscribe_url": "someurl"
     *     }
     *   }
     * }
     *
     * {
     *   "status":"success",
     *   "job_id":"1ZymBc-00041N-9X",
     *   "emails":["email@gmail.com"]
     * }
     *
     * @see https://one.unisender.com/ru/docs/page/send
     *
     * @param array $messageData
     *
     * @return array
     */
    public function sendEmail(array $messageData): array
    {
        return $this->sendPost('/email/send.json', ['message' => $messageData]);
    }

    /**
     *
     * {
     *    "username": "ID9999999",
     *    "api_key": "MHYHJRaPGESH2DT34kU43HvyAikcS6SHFOzHT",
     *    "template": {
     *       "id": "",
     *       "name": "test",
     *       "subject": "test",
     *       "template_engine":"simple",
     *       "global_substitutions": {
     *          "someVar":"someVal"
     *       },
     *       "from_email": "info@organiko.ru",
     *       "from_name": "ID9999999",
     *       "headers":
     *       {
     *          "X-ReplyTo": "reply@example.com"
     *       },
     *       "body":
     *       {
     *          "html": "<html><head></head><body><b>Some HTML</b><img src='cid:testimage.png' /></body></html>",
     *          "plaintext": "Some plain text"
     *       },
     *       "attachments": [
     *       {
     *          "type": "image/png",
     *          "name": "images123.png",
     *          "content": "iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABg"
     *       }
     *       ],
     *       "inline_attachments": [
     *       {
     *          "type": "image/png",
     *          "name": "testimage123.png",
     *          "content": "iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMA"
     *       }
     *       ],
     *       "options":
     *       {
     *         "unsubscribe_url": "someurl"
     *       }
     *    }
     * }
     *
     * {
     *    "status": "success",
     *    "template":
     *    {
     *       "id": "cef89054-40a8-4b9b-a379-22030d525c49",
     *       "name": "test1234",
     *       "subject": "test",
     *       "from_name": "1111",
     *       "body":
     *       {
     *          "html": "<html><head><\/head><body><b>Some HTML<\/b><img src='cid:testimage.png' \/><\/body><\/html>",
     *          "plaintext": "Some plain text"
     *       },
     *       "headers": {"Reply-To": "reply@example.com"},
     *       "attachments": {},
     *       "inline_attachments": {},
     *       "options":
     *       {
     *          "unsubscribe_url": "someurl"
     *       },
     *       "created": "2015-09-21 12:45:50",
     *       "user_id": 79
     *     }
     * }
     *
     * @see https://one.unisender.com/ru/docs/page/Template
     * @return array
     */
    public function upsertTemplate(): array
    {
        return $this->sendPost('/email/send.json');
    }

    /**
     * Request example:
     * {
     *     "api_key" : "MHYHJRaPGESH2DT34kU43HvyAikcS6SHFOzHT",
     *     "username":"ID9999999",
     *     "domain":"qqq.com"
     * }
     *
     * Response example:
     * {
     *     "status": "success",
     *     "domain": "qqq.com",
     *     "verification-record": "unione-validate-hash=483bb362ebdbeedd755cfb1d4d661111",
     *     "dkim": "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDo7UwUuFhSg4qvijxskJMDeGANgARhDFPpCZo4YE96yJ6OEX4h7UXq6E+tvEE7alCzEcsNtVWwV2mLLvl+VGJtm0qtpYiLT9hcjwapqUdhrJfH0v11I0EA7dMKqS4YIiGRNOICNKZZ+KQzH6wxT0k17qSQkOwKQnmSctQrrmakAQIDAQAB"
     * }
     *
     * @param string $domain
     *
     * @return array
     */
    public function getDnsRecords(string $domain)
    {
        return $this->sendPost('/domain/get-dns-records.json', ['domain' => $domain]);
    }

    public function verifyDomain()
    {

    }
}


/*
 * POST /ru/transactional/api/v1/domain/get-dns-records.json
 * POST /ru/transactional/api/v1/domain/validate-verification-record.json
 * POST /ru/transactional/api/v1/domain/validate-dkim.json
 *
 * POST /ru/transactional/api/v1/email/send.json
 * POST /ru/transactional/api/v1/template/set.json
 * POST /ru/transactional/api/v1/webhook/set.json
 * POST /ru/transactional/api/v1/webhook/get.json
 * POST /ru/transactional/api/v1/webhook/delete.json
 * POST /ru/transactional/api/v1/balance.json
 * POST /ru/transactional/api/v1/unsubscribed/set.json
 * POST /ru/transactional/api/v1/unsubscribed/check.json
 * POST /ru/transactional/api/v1/unsubscribed/list.json
 * POST /ru/transactional/api/v1/domain/list.json
 *
 * https://one.unisender.com
 * https://one.unisender.com/ru/transactional/api/v1
 */
