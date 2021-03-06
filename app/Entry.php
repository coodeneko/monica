<?php

namespace App;

use Parsedown;
use App\Traits\Journalable;
use Illuminate\Database\Eloquent\Model;
use App\Interfaces\IsJournalableInterface;

class Entry extends Model implements IsJournalableInterface
{
    use Journalable;

    protected $table = 'entries';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'post',
    ];

    /**
     * Get the account record associated with the entry.
     */
    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    /**
     * Get the Entry title.
     *
     * @param  string  $value
     * @return string
     */
    public function getTitleAttribute($value)
    {
        return $value;
    }

    /**
     * Get the Entry post.
     *
     * @param  string  $value
     * @return string
     */
    public function getPostAttribute($value)
    {
        return (new Parsedown())->text($value);
    }

    /**
     * Get all the information of the Entry for the journal.
     * @return array
     */
    public function getInfoForJournalEntry()
    {
        // Default to created_at, but show journalEntry->date if the entry type is JournalEntry
        $entryDate = $this->journalEntry ? $this->journalEntry->date : $this->created_at;

        return [
            'type' => 'entry',
            'id' => $this->id,
            'title' => $this->title,
            'post' => $this->post,
            'day' => $entryDate->day,
            'day_name' => \App\Helpers\DateHelper::getShortDay($entryDate),
            'month' => $entryDate->month,
            'month_name' => \App\Helpers\DateHelper::getShortMonth($entryDate),
            'year' => $entryDate->year,
        ];
    }
}
