@extends('pages.simple', ['title' => $lecturer->name, 'body' => $lecturer->bio ?? $lecturer->position ?? 'Profil dosen.'])
