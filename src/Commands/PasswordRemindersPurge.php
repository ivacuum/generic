<?php namespace Ivacuum\Generic\Commands;

class PasswordRemindersPurge extends Command
{
    protected $signature = 'app:purge-password-reminders';
    protected $description = 'Purge old password reminders';

    public function handle()
    {
        $deleted = \DB::table('password_reminders')
            ->where('created_at', '<', now()->subDay()->toDateTimeString())
            ->delete();

        $this->info("Удалено заявок на восстановление пароля: {$deleted}");
    }
}
