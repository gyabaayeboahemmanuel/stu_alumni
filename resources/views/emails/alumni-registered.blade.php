<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to STU Alumni Network</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #1E40AF, #3B82F6); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f8fafc; padding: 30px; border-radius: 0 0 10px 10px; }
        .button { display: inline-block; padding: 12px 30px; background: #1E40AF; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; color: #64748b; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to STU Alumni Network!</h1>
            <p>Sunyani Technical University</p>
        </div>
        
        <div class="content">
            <h2>Hello {{ $alumni->first_name }}!</h2>
            
            @if($registrationMethod === 'sis')
            <p>Your STU Alumni account has been successfully created and verified. Welcome to our growing network of accomplished graduates!</p>
            
            <p><strong>What you can do now:</strong></p>
            <ul>
                <li>Update your professional profile</li>
                <li>Connect with fellow alumni</li>
                <li>Register for upcoming events</li>
                <li>List your business in our directory</li>
                <li>Access exclusive alumni resources</li>
            </ul>
            @else
            <p>Thank you for registering with the STU Alumni Network! Your application has been received and is currently under review.</p>
            
            <p><strong>What happens next:</strong></p>
            <ul>
                <li>Our team will verify your documents</li>
                <li>You'll receive an email once approved</li>
                <li>Approval typically takes 2-3 business days</li>
            </ul>
            
            <p>Once approved, you'll have full access to all alumni features.</p>
            @endif

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/alumni/dashboard') }}" class="button">Access Your Dashboard</a>
            </div>

            <p>If you have any questions, please don't hesitate to contact our alumni office at <a href="mailto:alumni@stu.edu.gh">alumni@stu.edu.gh</a>.</p>
            
            <p>Best regards,<br>
            <strong>STU Alumni Office</strong><br>
            Sunyani Technical University</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Sunyani Technical University. All rights reserved.</p>
            <p>This email was sent to {{ $alumni->email }}. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
