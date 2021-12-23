<?php

interface EventParticipant {
    public function getEventsIParticipate(): array;
    public function getEventsControlledByMe(): array;
    public function getParticipants(int $eventId): array;
}