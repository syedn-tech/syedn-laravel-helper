# Syedn Tech - Laravel Helper

<p align="center">
  <a href="https://github.com/syedn-tech/syedn-laravel-helper">
    <img src="assets/img/{sn}.jpg" width="100" alt="SYEDN Logo" style="border-radius: 15px;">
  </a>
</p>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/syedn-tech/laravel-helper.svg?style=flat-square)](https://packagist.org/packages/syedn-tech/laravel-helper)
[![Total Downloads](https://img.shields.io/packagist/dt/syedn-tech/laravel-helper.svg?style=flat-square)](https://packagist.org/packages/syedn-tech/laravel-helper)

A collection of useful helper functions and classes to accelerate Laravel development at SYEDN Tech Solutions.

## Features

- **Base Classes**: Model, Repository, Service, Trait, Constant, and Exception base classes with interfaces
- **Custom Artisan Commands**: Generate repositories, services, and constants
- **Custom Stub System**: Override Laravel's default stubs with package-specific implementations
- **Auto-Discovery**: Automatically registers service provider and commands
- **Automatic Dependencies**: Auto-installs Orchestra Testbench as dev dependency
- **Smart Composer Scripts**: Adds convenient test scripts to consumer projects
- **Laravel 12 Compatible**: Built for Laravel 12 and PHP 8.4+

## Installation

You can install the package via composer:

```bash
composer require syedn-tech/laravel-helper