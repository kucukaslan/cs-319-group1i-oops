<?php
interface AcademicController {
    public function addCoordinator(int $eventId, int $coordinator_id): bool;
    public function getParticipantsAndStatus(int $eventId): array;
    // public function createCourse(string, string, int, bool, int, string): bool
    // public function deleteCourse(int): bool
}