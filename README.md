<div align="center">
    بسم الله الرحمن الرحيم
</div>

# Livewire Nonceable

**The security [Livewire](https://livewire.laravel.com) public methods needed!**

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vpremiss/livewire-nonceable.svg?style=for-the-badge)](https://packagist.org/packages/vpremiss/livewire-nonceable)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/vpremiss/livewirenonceable/run-tests.yml?branch=main&label=tests&style=for-the-badge&color=forestgreen)](https://github.com/vpremiss/livewirenonceable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/vpremiss/livewire-nonceable.svg?style=for-the-badge&color=b07d00)](https://packagist.org/packages/vpremiss/livewire-nonceable)


## Description

The reason for this package is to address Livewire's current weakness of forcing the developer to expose certain methods to the **`public`** in order for the front-end ([AlpineJS](https://alpinejs.dev)) to be able to communicate with it; parameters included...

And if you ask, why wouldn't you do sensitive, protected stuff in Livewire component only, and why would you need to expose them in the first place? WELL, how about YOU try using [Sanctum](https://laravel.com/docs/sanctum) and see how things go! Hitting APIs that are `auth:sanctum` middleware-protected is **impossible**. And the only approach is to rely on `axios` in your [TALL](https://tallstack.dev) views to communicate with APIs after being authenticated with Sanctum.

This ***back-and-forth*** will draw you to think about what to do with regard to protecting those `public` methods from being just hit from the client with ease (AKA. DDOSed). And we've concluded that a solution would be to [`NONCE`](https://computersciencewiki.org/index.php/Nonce) into Laravel's **cache instead of its session** -because of the persistance approach that Livewire works with and the need to be able to access it from different places.

Thanks for coming to my -talk. Enjoy the package and the awesome stacking like fine blacksmithery!


## Installation

- Ensure that both [Livewire](https://livewire.laravel.com) and [Redis](https://laravel.com/docs/redis) are installed, of course.

- Install the package via [composer](https://getcomposer.org):

  ```bash
  composer require vpremiss/livewire-nonceable
  ```

- Publish the [config file](config/livewire-nonceable.php) using this [Artisan](https://laravel.com/docs/artisan) command:

  ```bash
  php artisan vendor:publish --tag="livewire-nonceable-config"
  ```


## Usage

- In your Livewire component, implement our [Noncing](src/Interfaces/Noncing.php) interface and its methods. Then apply the [Nonceable](src/Traits/Nonceable.php) trait as well.

  ```php
  use Livewire\Component;
  use VPremiss\LivewireNonceable\Interfaces\Noncing;
  use VPremiss\LivewireNonceable\Traits\Nonceable;

  class FuzzySearch extends Component implements Noncing
  {
      use Nonceable;

      public function getNonces(): array
      {
          return [
              'complex-searching' => 5, // the nonce title, plus 5 seconds lasting in cache
              // 'heavy-processing' => 10, as another example
          ];
      }

      public function getNonceUniqueId(): string
      {
          return auth()->user()->id; // After ensuring authentication, of course!
      }

      // This method is initiated securely
      protected function validateSearch($query)
      {
          // $validatedQuery = some validations

          $nonce = $this->generateNonce('complex-searching');

          $this->dispatch(
              'searching-began', // receive in the front-end (SPA)
              query: $validatedQuery,
              nonce: $nonce,
          );
      }

      // This is hit back from AlpineJS using axios
      public function complexSearch($responseFromApi, $nonce)
      {
          // Or use the opposite ! $this->doesNonceExist($title, $nonce) method
          if ($this->isNonceSense('complex-searching', $nonce)) {
              // throw new NoncenseException('Nonce mismatch. Somebody is playing around!');
          }

          $this->deleteNonce('complex-searching', $nonce);

          // do the complex searching now with an ease of mind...
      }
  }
  ```

- And you may also utilize these 2 checking methods from the view:

  ```blade
  <div
      x-on:searching-began.window="callApi($event.detail.query, $event.detail.nonce)"
      x-data='{
          callApi(query, nonce) {
              // or the opposite ! $wire.isNonceSense(title, nonce)
              if ($wire.doesNonceExist("complex-searching", nonce)) {
                  axios
                      .post(
                          "{{ "some-route" }}",
                          {
                              query: query,
                              nonce: nonce,
                          },
                      )
                      .then(response => $wire.complexSearch(response, nonce))
                      .catch(error => console.error(error));
              }
          },
      }'
  >
     {{-- ... --}}
  </div>
  ```

And again, just to recap: we **cannot** work around not making the complexSearch method public because we need to call it from the only place where **Sanctum** allows API calls to its protected routes: the front-end...

***If you found a better way to deal around this, please let us know in the [discussions](https://github.com/VPremiss/Livewire-Nonceable/discussions) section.***

<br>

### API

Below is the table of key methods provided by the `LivewireNonceable` package along with their descriptions:

| Method                     | Description                                                                | Parameters                              | Returns/Throws                          |
|----------------------------|----------------------------------------------------------------------------|-----------------------------------------|-----------------------------------------|
| **protected:** `generateNonce`            | Generates a nonce, stores it in Redis with a TTL, and returns the nonce.   | `string $title`                         | `string` (nonce)                        |
| **protected:** `deleteNonce`              | Deletes a nonce from Redis if it exists and is still valid.                | `string $title, string $nonce`          | Throws `NoncenseException` if not found |
| **public:** `doesNonceExist`           | Checks if a given nonce exists in Redis and is still valid.                | `string $title, string $nonce`          | `bool` (true if exists, false otherwise)|
| **public:** `isNonceSense`             | Checks if a given nonce does not exist or has expired.                     | `string $title, string $nonce`          | `bool` (true if not exists, false otherwise)|

<br>

### Changelogs

You can check out the package's [changelogs](https://app.whatthediff.ai/changelog/github/VPremiss/Livewire-Nonceable) online via WTD.

### Progress

You can also checkout the project's [roadmap](https://github.com/orgs/VPremiss/projects/8) among others in the organization's dedicated section for [projects](https://github.com/orgs/VPremiss/projects).


## Support

Support ongoing package maintenance as well as the development of **other projects** through [sponsorship](https://github.com/sponsors/VPremiss) or one-time [donations](https://github.com/sponsors/VPremiss?frequency=one-time&sponsor=VPremiss) if you prefer.

And may Allah accept your strive; aameen.

### License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

### Credits

- [ChatGPT](https://chat.openai.com)
- [Livewire](https://github.com/Livewire)
- [Laravel](https://github.com/Laravel)
- [Spatie](https://github.com/Spatie)
- [Graphite](https://graphite.dev)
- [WTD](https://whatthediff.ai)
- [All Contributors](../../contributors)
- And the generous individuals that we've learned from and been supported by throughout our journey...


<div align="center">
   <br>والحمد لله رب العالمين
</div>
